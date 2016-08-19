<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class TicketController extends Controller
{
    /**
     * @Route("/payment/{gatewayName}",
     *     name="prepare_payment",
     *     requirements={
     *     "gatewayName": "paypal|stripe"})
     */
    public function prepareAction(Request $request, $gatewayName)
    {


        $storage = $this->get('payum')->getStorage('AppBundle\Entity\Payment');

        $booking = $this->getDoctrine()->getRepository('AppBundle:Booking')->find($request->getSession()->get('booking_nb'));
        $payment = $storage->create();
        $payment->setNumber(uniqid());
        $payment->setCurrencyCode('EUR');
        $payment->setTotalAmount($booking->getTotalPrice()); // 1.23 EUR
        $payment->setDescription('Billeterie Musée du Louvre');
        $payment->setBooking($booking->getId());
        $payment->setClientEmail($booking->getEmail());
        $storage->update($payment);

        $captureToken = $this->get('payum')->getTokenFactory()->createCaptureToken(
            $gatewayName,
            $payment,
            'payment_done' // the route to redirect after capture
        );

        return $this->redirect($captureToken->getTargetUrl());
    }


    /**
     * @param Request $request
     * @return JsonResponse
     * @Route("/done", name="payment_done")
     */
    public function doneAction(Request $request)
    {
        $token = $this->get('payum')->getHttpRequestVerifier()->verify($request);

        $gateway = $this->get('payum')->getGateway($token->getGatewayName());

        // you can invalidate the token. The url could not be requested any more.
        // $this->get('payum')->getHttpRequestVerifier()->invalidate($token);

        // Once you have token you can get the model from the storage directly.
        //$identity = $token->getDetails();
        //$payment = $payum->getStorage($identity->getClass())->find($identity);

        // or Payum can fetch the model for you while executing a request (Preferred).
        $gateway->execute($status = new GetHumanStatus($token));
        //$payment = $status->getFirstModel();


        // you have order and payment status
        // so you can do whatever you want for example you can just print status and payment details.

        if ($status->isCaptured()) {
            return $this->redirectToRoute('ticket_sendticket');
        }

        $this->addFlash('error', 'Nous sommes désolé, le paiement n\'a pas aboutis. Veuillez rééssayer');

        return $this->redirectToRoute('app_pricing');
    }
    /**
     * @Route("/sendticket",
     *     name="ticket_sendticket")
     */
    public function ticketAction(Request $request)
    {
        $booking = $this->getDoctrine()
            ->getRepository('AppBundle:Booking')
            ->find($request->getSession()->get('booking_nb'))
        ;



        $tickets = $booking->getVisitors()->getValues();
        $message = \Swift_Message::newInstance()
            ->setSubject('Musée du Louvre e-Ticket')
            ->setTo($booking->getEmail())
            ->setFrom('booking@louvre.fr')
            ->setBody('Hello World!!');

        $em = $this->getDoctrine()->getManager();
        foreach ($tickets as $ticket) {

            $ticketNb = mb_strtolower('tk' . $ticket->getFirstName() . $booking->getVisitDate()->format('Ymd') . uniqid());
            $ticket->setTicketNb($ticketNb);
            $em->persist($ticket);
            $this->get('knp_snappy.pdf')->generateFromHtml(
                $this->renderView('AppBundle:Ticket:ticket.html.twig',[
                    'ticketNb' => $ticketNb,
                    'visite_date' => $booking->getVisitDate()->format('d-m-Y'),
                    'firstName' => $ticket->getFirstname(),
                    'lastName'  => $ticket->getLastName(),
                    'half_day' => $booking->getHalfDay(),
                    'priceName' => $ticket->getPrice(),
                    'bill' => $ticket->getBill()
                ]),
                '/tmp/louvre/' . $ticketNb . '.pdf'
            );

            $message->attach(\Swift_Attachment::fromPath('/tmp/louvre/' . $ticketNb . '.pdf'));
        }

        $em->flush();

        ;

        $this->get('mailer')->send($message);

        return $this->render('AppBundle:pricing:success.html.twig', ['email' => $booking->getEmail()]);
    }
}

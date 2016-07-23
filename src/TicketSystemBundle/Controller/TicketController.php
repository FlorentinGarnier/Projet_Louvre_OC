<?php

namespace TicketSystemBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class TicketController extends Controller
{
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

            $ticketNb = strtolower('tk' . $ticket->getFirstName() . $booking->getVisitDate()->format('Ymd') . uniqid());
            $ticket->setTicketNb($ticketNb);
            $em->persist($ticket);
            $this->get('knp_snappy.pdf')->generateFromHtml(
                $this->renderView('TicketSystemBundle:Ticket:ticket.html.twig',[
                    'ticketNb' => $ticketNb,
                    'visite_date' => $booking->getVisitDate()->format('d-m-Y'),
                    'firstName' => $ticket->getFirstname(),
                    'lastName'  => $ticket->getLastName(),
                    'half_day' => $booking->getHalfDay()
                ]),
                '/tmp/louvre/' . $ticketNb . '.pdf'
            );

            $message->attach(\Swift_Attachment::fromPath('/tmp/louvre/' . $ticketNb . '.pdf'));
        }

        $em->flush();

        ;

        $this->get('mailer')->send($message);
        $this->addFlash('notice', 'Vos tickets vous on été envoyé à votre adresse '. $booking->getEmail());
        return $this->redirectToRoute('app_bookink_homepage');
    }
}

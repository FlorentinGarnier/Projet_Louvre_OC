<?php

namespace PaymentBundle\Controller;

use Payum\Core\Request\GetHumanStatus;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PaymentController extends Controller
{
    /**
     * @Route("/payment/{gatewayName}",
     *     name="prepare_payment",
     *     requirements={
     *     "gatewayName": "paypal|stripe"})
     */
    public function prepareAction(Request $request, $gatewayName)
    {


        $storage = $this->get('payum')->getStorage('PaymentBundle\Entity\Payment');

        $booking = $this->getDoctrine()->getRepository('AppBundle:Booking')->find($request->getSession()->get('booking_nb'));
        $payment = $storage->create();
        $payment->setNumber(uniqid());
        $payment->setCurrencyCode('EUR');
        $payment->setTotalAmount($booking->getTotalPrice() * 100); // 1.23 EUR
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
}


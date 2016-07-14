<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class PricingController extends Controller
{
    /**
     * @Route("/pricing")
     */
    public function pricingAction(Request $request)
    {
        $booking = $this->getDoctrine()->getRepository('AppBundle:Booking')
            ->find($request->getSession()->get('booking_nb'));


        dump($booking->getVisitors());
        return $this->render('AppBundle:Pricing:pricing.html.twig', array(
            // ...
        ));
    }

}

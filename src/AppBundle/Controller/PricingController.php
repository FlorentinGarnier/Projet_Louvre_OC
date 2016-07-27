<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class PricingController extends Controller
{
    /**
     * @Route("/pricing",
     * name="app_pricing")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function pricingAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $bookingNb = $request->getSession()->get('booking_nb');
        $booking = $this->getDoctrine()->getRepository('AppBundle:Booking')->find($bookingNb);
        $visitors = $booking->getVisitors()->getValues($bookingNb);
        $pricing = $this->get('app.louvrepricing');
        $pricing->setVisitors($visitors);
        $pricing->isFamily();
        $booking->setTotalPrice($pricing->total());
        $em->persist($booking);
        $em->flush();

        return $this->render('AppBundle:pricing:pricing.html.twig', array(
            'visitors' => $visitors,
            'booking' => $booking
        ));
    }

}

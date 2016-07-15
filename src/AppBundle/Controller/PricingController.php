<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class PricingController extends Controller
{
    /**
     * @Route("/pricing")
     */
    public function pricingAction(Request $request)
    {
        $visitors = $this->getDoctrine()->getRepository('AppBundle:Visitor')
            /*
             * Pour dev
             * ->findVisitorsByBookingNb($request->getSession()->get('booking_nb'));
             */
            ->findVisitorsByBookingNb(52);

        /*
         * Tarif par visitors
         */

        $this->get('app.louvrepricing')->calculatePrice($visitors);
        $this->get('app.louvrepricing')->total($visitors);


        return $this->render('AppBundle:Pricing:pricing.html.twig', array(
            'visitors' => $visitors,
        ));
    }

}

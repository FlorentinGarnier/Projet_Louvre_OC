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
        $visitors = $this->getDoctrine()->getRepository('AppBundle:Visitor')
            ->findVisitorsByBookingNb($request->getSession()->get('booking_nb'));


        $tarif = $this->getDoctrine()->getRepository('AppBundle:Price');
        /*
         * Tarif famille?
         */

        $names = [];
        foreach ($visitors as $visitor){
            $name[] = $visitor->getFirstName();
        }

        foreach ($names as $name){

        }

        /*
         * Tarif par age
         */

        foreach ($visitors as $visitor){
            $age = $visitor->getBirthday()->diff(new \DateTime())->y;
            switch ($age){
                case $age >= 12 && $age < 60:
                    $visitor->setPrice($tarif->findOneBy(['name' => 'normal']));
                    break;
                case $age >= 4 && $age < 12:
                    $visitor->setPrice($tarif->findOneBy(['name' => 'enfant']));
                    break;
                case $age >= 60 :
                    $visitor->setPrice($tarif->findOneBy(['name' => 'senior']));
                    break;
                case true === $visitor->getReduce() :
                    $visitor->setPrice($tarif->findOneBy(['name' => 'reduit']));

            }
            if ($age < 4){
                $visitor->setPrice(null);
            }

        }

        dump($visitors);

        return $this->render('AppBundle:Pricing:pricing.html.twig', array(
            // ...
        ));
    }

}

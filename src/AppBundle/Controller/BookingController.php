<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Booking;
use AppBundle\Form\Type\BookingType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class BookingController extends Controller
{
    /**
     * @Route("/", name="app_bookink_homepage")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        //Annulation de la commande en cours si retour sur homepage
        if ($request->hasSession()){
            $request->getSession()->remove('booking_nb');
        }
        $booking = new Booking();
        $form = $this->createForm(BookingType::class, $booking);
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $visitDate = $booking->getVisitDate();
            $dateChecking = $this->get('app.datechecking');
            if ($dateChecking->isValid($visitDate->getTimestamp())){
                $em = $this->getDoctrine()->getManager();
                $em->persist($booking);
                $em->flush();
                $request->getSession()->set('booking_nb', $booking->getId());
                return $this->redirectToRoute('app_pricing');
            } else $this->addFlash('error', 'La date de réservation n\'est pas valide');
        }
        return $this->render('AppBundle:booking:index.html.twig', [
            'form' => $form->createView()
        ]);
    }


}

<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Booking;
use AppBundle\Entity\Visitor;
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
        if ($request->hasSession()){
            $request->getSession()->remove('booking_nb');
        }
        $booking = new Booking();
        $form = $this->createForm(BookingType::class, $booking);


        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            if ($this->get('app.datechecking')->isValid($booking->getVisitDate()->getTimestamp())){
                $em = $this->getDoctrine()->getManager();
                $em->persist($booking);
                $em->flush();
                $request->getSession()->set('booking_nb', $booking->getId());

                return $this->redirectToRoute('app_pricing');
            }

        }

        return $this->render('AppBundle:booking:index.html.twig', [
            'form' => $form->createView()
        ]);
    }


}

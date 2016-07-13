<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Booking;
use AppBundle\Entity\Visitor;
use AppBundle\Form\Type\BookingType;
use Doctrine\Common\Collections\ArrayCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BookingController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $booking = new Booking();
        $form = $this->createForm(BookingType::class, $booking);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()){
            $em = $this
                ->getDoctrine()
                ->getManager()
            ;
                $em->persist($booking);
                $em->flush();
            $request->getSession()->set('booking_nb', $booking->getId());

            return $this->redirectToRoute('homepage');
        }
        
        return $this->render('booking/index.html.twig', [
            'form' => $form->createView()
        ]);
    }

    
}

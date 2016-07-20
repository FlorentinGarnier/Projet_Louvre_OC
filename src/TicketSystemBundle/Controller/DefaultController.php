<?php

namespace TicketSystemBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/sendticket",
     *     name="ticket")
     */
    public function ticketAction(Request $request)
    {
        $booking = $this->getDoctrine()
            ->getRepository('AppBundle:Booking')
            ->find($request->getSession()->get('booking_nb'))
        ;
        $message = \Swift_Message::newInstance()
            ->setSubject('Musée du Louvre e-Ticket')
            ->setTo($booking->getEmail())
            ->setFrom('booking@louvre.fr')
            ->setBody('Hello World!!')
        ;

        $this->get('mailer')->send($message);
        return $this->render('TicketSystemBundle:Default:index.html.twig');
    }
}

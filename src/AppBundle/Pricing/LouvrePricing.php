<?php
/**
 * Created by PhpStorm.
 * User: Garnier
 * Date: 15/07/2016
 * Time: 14:48
 */

namespace AppBundle\Pricing;


use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Session\Session;

class LouvrePricing
{
    private $em;
    private $priceRepository;
    private $booking;
    private $visitors;
    private $isFamily = false;


    public function __construct(EntityManager $entityManager, Session $session)
    {
        $this->em = $entityManager;
        $this->priceRepository = $this->em->getRepository('AppBundle:Price');
        $this->booking = $this->em->getRepository('AppBundle:Booking')->find($session->get('booking_nb'));


    }



    /**
     * Check if visitors is a family
     * @return boolean
     */
    public function isFamily()
    {
        if (4 === count($this->visitors)){
            $familyName = [];
            $children = null;
            $adult = null;
            foreach ($this->visitors as $visitor){
                $familyName[] = mb_strtolower($visitor->getLastName());
                if ('normal' === $visitor->getPrice()){
                    $adult++;
                } elseif ('enfant' === $visitor->getPrice()){
                    $children++;
                }

            }

            if (in_array(4,array_count_values($familyName)) && 2 == $children && 2 == $adult){
                $this->isFamily = true;
            }
        } else $this->isFamily = false;
    }

    /**
     * Calculate price of visitor(s) in booking with age
     *
     * @param $visitors
     */
    public function setVisitors($visitors)
    {
        $this->visitors = $visitors;

        dump($this->visitors);

        foreach ($this->visitors as $visitor) {

            $age = $visitor->getBirthday()->diff(new \DateTime())->y;
            switch ($age) {
                case $age >= 12 && $age < 60:
                    $visitor->setPrice('normal');
                    break;
                case $age >= 4 && $age < 12:
                    $visitor->setPrice('enfant');
                    break;
                case $age >= 60 :
                    $visitor->setPrice('senior');
                    break;

            }
            if ($visitor->getReduce() === true) {
                $visitor->setPrice('reduit');
            }
            if ($age < 4) {
                $visitor->setPrice('gratuit');
            }
            $this->em->persist($visitor);
        }

    }

    /**
     * Calculate total
     * @return string
     */
    public function total(){

        $total = '';

        if (!$this->isFamily) {
            foreach ($this->visitors as $visitor) {
                $price = $this->foundPrice($visitor->getPrice());
                if ($this->booking->getHalfDay()){
                    $visitor->setBill($price/2);
                } else $visitor->setBill($price);

                $total += $visitor->getBill();

            }
        } else {
            foreach ($this->visitors as $visitor){
                $visitor->setPrice('Famille');
                $visitor->setBill(null);
                $total = 3500;

            }
        }

        return $total;

    }

    /**
     * Bind price value to the price name
     * @param $name
     * @return mixed
     */
    private function foundPrice($name)
    {
        $price = $this->priceRepository
            ->findPrice($name);


        return $price;
    }


}

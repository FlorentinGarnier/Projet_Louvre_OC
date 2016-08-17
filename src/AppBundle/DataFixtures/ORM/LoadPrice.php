<?php
/**
 * Created by PhpStorm.
 * User: Garnier
 * Date: 13/07/2016
 * Time: 23:53
 */

namespace AppBundle\DataFixtures\ORM;


use AppBundle\Entity\Booking;
use AppBundle\Entity\Price;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadPrice implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        //Fixtures for price

        $val = [
            'normal' => 1600,
            'enfant' => 800,
            'senior' => 1200,
            'reduit' => 1000,
            'famille' => 3500,
            'gratuit' => 0
        ];

        foreach ($val as $k=>$value){
            $price = new Price();
            $price->setName($k);
            $price->setValue($value);

            $manager->persist($price);
        }

        //Fixtures for test

        for ($i = 0; $i < 1000; $i++){
            $booking = new Booking();
            $booking->setVisitDate(new \DateTime('2-12-2016'));
            $booking->setHalfDay(1);
            $manager->persist($booking);
        }

        $manager->flush();


    }
}
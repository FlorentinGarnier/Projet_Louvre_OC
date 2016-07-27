<?php
/**
 * Created by PhpStorm.
 * User: Garnier
 * Date: 13/07/2016
 * Time: 23:53
 */

namespace AppBundle\DataFixtures\ORM;


use AppBundle\Entity\Price;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadPrice implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {

        $val = [
            'normal' => 16.00,
            'enfant' => 8.00,
            'senior' => 12.00,
            'reduit' => 10.00,
            'famille' => 35.00,
            'gratuit' => 0
        ];

        foreach ($val as $k=>$value){
            $price = new Price();
            $price->setName($k);
            $price->setValue($value);

            $manager->persist($price);
        }

        $manager->flush();


    }
}
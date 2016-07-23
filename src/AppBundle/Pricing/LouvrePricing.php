<?php
/**
 * Created by PhpStorm.
 * User: Garnier
 * Date: 15/07/2016
 * Time: 14:48
 */

namespace AppBundle\Pricing;


use AppBundle\Entity\Visitor;
use Doctrine\ORM\EntityManager;

class LouvrePricing
{
    private $em;
    private $priceRepository;
    private $familyName;


    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
        $this->priceRepository = $this->em->getRepository('AppBundle:Price');

    }

    public function calculatePrice($visitors)
    {







        foreach ($visitors as $visitor) {


            $age = $visitor->getBirthday()->diff(new \DateTime())->y;
            switch ($age) {
                case $age >= 12 && $age < 60:
                    $visitor->setPrice($this->foundPrice('normal'));
                    break;
                case $age >= 4 && $age < 12:
                    $visitor->setPrice($this->foundPrice('enfant'));
                    break;
                case $age >= 60 :
                    $visitor->setPrice($this->foundPrice('senior'));
                    break;

            }
            if ($visitor->getReduce() === true) {
                $visitor->setPrice($this->foundPrice('reduit'));
            }
            if ($age < 4) {
                $visitor->setPrice(0);
            }
            $this->em->persist($visitor);
        }
        $this->em->flush();
    }

    public function total($visitors){

        $total = '';

        foreach ($visitors as $visitor){
            $total += $visitor->getPrice();

        }

        return $total;

    }

    private function foundPrice($name)
    {
        $price = $this->priceRepository
            ->findPrice($name);

        return $price;
    }
}

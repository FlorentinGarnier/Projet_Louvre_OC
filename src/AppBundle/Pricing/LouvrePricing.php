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
    private $visitors;
    private $isFamily = false;


    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
        $this->priceRepository = $this->em->getRepository('AppBundle:Price');

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
                $familyName[] = $visitor->getLastName();
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
     * Calculate price of booking
     *
     * @param $visitors
     */
    public function setVisitors($visitors)
    {
        $this->visitors = $visitors;


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
                $visitor->setPrice(0);
            }
            $this->em->persist($visitor);
        }
        $this->em->flush();
    }

    /**
     * Calculate total
     * @return string
     */
    public function total(){

        $total = '';

        if (!$this->isFamily){
            foreach ($this->visitors as $visitor){
                $visitor->setBill($this->foundPrice($visitor->getPrice()));
                $total += $visitor->getBill();

            }
        } else {
            foreach ($this->visitors as $visitor){
                $visitor->setPrice('Famille');
                $visitor->setBill(null);
                $total = 35;

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


    private function setIsFamily($isFamily)
    {
        $this->isFamily = $isFamily;
    }
}

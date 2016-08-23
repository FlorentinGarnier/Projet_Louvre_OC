<?php
/**
 * Created by PhpStorm.
 * User: Garnier
 * Date: 12/07/2016
 * Time: 22:15
 */

namespace AppBundle\Repository;


use Doctrine\ORM\EntityRepository;

class BookingRepository extends EntityRepository
{
    public function countPlacesAt($date){
        return $this->createQueryBuilder('b')
            ->select('COUNT(b.visit_date)')
            ->where('b.visit_date = :date')
            ->setParameter(':date', $date)
            ->getQuery()
            ->getSingleScalarResult()
            ;
    }
}

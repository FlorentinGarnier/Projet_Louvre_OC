<?php
/**
 * Created by PhpStorm.
 * User: Garnier
 * Date: 12/07/2016
 * Time: 21:59
 */

namespace AppBundle\Repository;


use Doctrine\ORM\EntityRepository;

class VisitorRepository extends EntityRepository
{
    public function findVisitorsByBookingNb($bookingNb)
    {
        $qb = $this->createQueryBuilder('v');
        $qb->join('v.booking', 'booking')
            ->addSelect('booking')
            ->where('booking.id = :bookingNb')
            ->setParameter(':bookingNb', $bookingNb)
        ;

        return $qb
            ->getQuery()
            ->getResult();
    }
}
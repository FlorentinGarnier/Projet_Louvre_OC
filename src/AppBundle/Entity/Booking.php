<?php
/**
 * Created by PhpStorm.
 * User: Garnier
 * Date: 12/07/2016
 * Time: 21:46
 */

namespace AppBundle\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Visitor
 * @package AppBundle\Entity
 * @ORM\Table(name="booking")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\BookingRepository")
 */
class Booking
{
    /**
     * @var
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var
     * @ORM\Column(type="date")
     */
    private $visit_date;

    /**
     * @var
     * @ORM\Column(type="datetime")
     */
    private $created_date;

    /**
     * @var
     * @ORM\Column(type="boolean")
     */
    private $half_day;


    /**
     * @var
     * @ORM\Column(type="string", length=125, nullable=true)
     */
    private $email;

    /**
     * @var int
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Visitor", cascade={"persist"})
     *
     */
    private $visitors;

    public function __construct()
    {
        $this->visitors = new ArrayCollection();
        $this->created_date = new \DateTime();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getVisitDate()
    {
        return $this->visit_date;
    }

    /**
     * @param mixed $visit_date
     */
    public function setVisitDate($visit_date)
    {
        $this->visit_date = $visit_date;
    }

    /**
     * @return mixed
     */
    public function getCreatedDate()
    {
        return $this->created_date;
    }

    /**
     * @param mixed $created_date
     */
    public function setCreatedDate($created_date)
    {
        $this->created_date = $created_date;
    }

    /**
     * @return mixed
     */
    public function getHalfDay()
    {
        return $this->half_day;
    }

    /**
     * @param mixed $half_day
     */
    public function setHalfDay($half_day)
    {
        $this->half_day = $half_day;
    }

    /**
     * @return mixed
     */
    public function getNumberTicket()
    {
        return $this->number_ticket;
    }

    /**
     * @param mixed $number_ticket
     */
    public function setNumberTicket($number_ticket)
    {
        $this->number_ticket = $number_ticket;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getVisitors()
    {
        return $this->visitors;
    }

    /**
     * @param mixed $visitors
     */
    public function setVisitors($visitors)
    {
        $this->visitors = $visitors;
    }

    /**
     * @return ArrayCollection
     */
    public function getBookings()
    {
        return $this->bookings;
    }

    /**
     * @param ArrayCollection $bookings
     */
    public function setBookings($bookings)
    {
        $this->bookings = $bookings;
    }


}
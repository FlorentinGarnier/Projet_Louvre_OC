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
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Visitor
 * @package AppBundle\Entity
 * @ORM\Table(name="Booking")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\BookingRepository")
 */
class Booking
{
    /**
     * @var
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
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
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Visitor", mappedBy="booking", cascade={"persist"})
     *
     */
    private $visitors;

    /**
     * @var
     * @ORM\Column(type="decimal", name="total_price", nullable=true)
     */
    private $totalPrice;


    public function __construct()
    {
        $this->visitors = new ArrayCollection();
        $this->created_date = new \DateTime();
    }

   

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set visitDate
     *
     * @param \DateTime $visitDate
     *
     * @return Booking
     */
    public function setVisitDate($visitDate)
    {
        $this->visit_date = $visitDate;

        return $this;
    }

    /**
     * Get visitDate
     *
     * @return \DateTime
     */
    public function getVisitDate()
    {
        return $this->visit_date;
    }

    /**
     * Set createdDate
     *
     * @param \DateTime $createdDate
     *
     * @return Booking
     */
    public function setCreatedDate($createdDate)
    {
        $this->created_date = $createdDate;

        return $this;
    }

    /**
     * Get createdDate
     *
     * @return \DateTime
     */
    public function getCreatedDate()
    {
        return $this->created_date;
    }

    /**
     * Set halfDay
     *
     * @param boolean $halfDay
     *
     * @return Booking
     */
    public function setHalfDay($halfDay)
    {
        $this->half_day = $halfDay;

        return $this;
    }

    /**
     * Get halfDay
     *
     * @return boolean
     */
    public function getHalfDay()
    {
        return $this->half_day;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Booking
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Add visitor
     *
     * @param \AppBundle\Entity\Visitor $visitor
     *
     * @return Booking
     */
    public function addVisitor(Visitor $visitor)
    {
        $this->visitors[] = $visitor;
        $visitor->setBooking($this);
        return $this;
    }

    /**
     * Remove visitor
     *
     * @param \AppBundle\Entity\Visitor $visitor
     */
    public function removeVisitor(Visitor $visitor)
    {
        $this->visitors->removeElement($visitor);
    }

    /**
     * Get visitors
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getVisitors()
    {
        return $this->visitors;
    }

    /**
     * @return mixed
     */
    public function getTotalPrice()
    {
        return $this->totalPrice;
    }

    /**
     * @param mixed $totalPrice
     */
    public function setTotalPrice($totalPrice)
    {
        $this->totalPrice = $totalPrice;
    }
}

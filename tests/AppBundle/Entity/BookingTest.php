<?php
/**
 * Created by PhpStorm.
 * User: Garnier
 * Date: 15/08/2016
 * Time: 09:52
 */

namespace tests\AppBundle\Entity;


use AppBundle\Entity\Booking;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BookingTest extends WebTestCase
{
    protected $booking;

    protected function setUp()
    {
        $this->booking = new Booking();
    }

    public function testVisitDateReturnDate()
    {
        $this->booking->setVisitDate(new DateTime());
        $this->assertInstanceOf(DateTime::class, $this->booking->getVisitDate());
    }

    public function testCreatedDateReturnDateObject()
    {
        $this->assertInstanceOf(DateTime::class, $this->booking->getCreatedDate());
        $this->booking->setCreatedDate('wiiiiz');
        $this->assertNotInstanceOf(DateTime::class, $this->booking->getCreatedDate());

    }

    public function testHalfDayIsBoolean()
    {
        $this->booking->setHalfDay(true);
        $this->assertTrue($this->booking->getHalfDay());
    }

    public function testEmailIsString()
    {
        $this->booking->setEmail('garnier.florentin@gmail.com');
        $this->assertStringMatchesFormat('%s',
            $this->booking->getEmail());
    }

    public function testTotalPriceIsNumeric()
    {
        $this->booking->setTotalPrice(1600);
        $this->assertTrue(is_numeric($this->booking->getTotalPrice()));
    }
}
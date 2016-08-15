<?php
/**
 * Created by PhpStorm.
 * User: Garnier
 * Date: 15/08/2016
 * Time: 11:28
 */

namespace tests\AppBundle\Entity;


use AppBundle\Entity\Booking;
use AppBundle\Entity\Visitor;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class VisitorTest extends WebTestCase
{
    protected $visitor;

    protected function setUp()
    {
        $this->visitor = new Visitor();
    }

    public function gauge()
    {
        return [
            ['florentin'],
            [1234],
            [new \DateTime()],
            [true]
        ];
    }

    /**
     * @dataProvider gauge
     */
    public function testPropertiesReturnSomethings($gauge)
    {
        $this->visitor->setFirstName($gauge);
        $this->visitor->setLastName($gauge);
        $this->visitor->setReduce($gauge);
        $this->visitor->setBirthday($gauge);
        $this->visitor->setPrice($gauge);
        $this->visitor->setBill($gauge);
        $this->visitor->setTicketNb($gauge);
        $this->visitor->setCountry($gauge);

        $this->assertContains($gauge, [$this->visitor->getFirstName()]);
        $this->assertContains($gauge, [$this->visitor->getLastName()]);
        $this->assertContains($gauge, [$this->visitor->getReduce()]);
        $this->assertContains($gauge, [$this->visitor->getBirthday()]);
        $this->assertContains($gauge, [$this->visitor->getPrice()]);
        $this->assertContains($gauge, [$this->visitor->getBill()]);
        $this->assertContains($gauge, [$this->visitor->getTicketNb()]);
        $this->assertContains($gauge, [$this->visitor->getCountry()]);
    }

    public function testBookingIsAnInstanceOfBooking()
    {
        $this->visitor->setBooking(new Booking());
        $this->assertInstanceOf(Booking::class, $this->visitor->getBooking());
    }

}
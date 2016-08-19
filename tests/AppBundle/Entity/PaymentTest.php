<?php
/**
 * Created by PhpStorm.
 * User: Garnier
 * Date: 15/08/2016
 * Time: 11:28
 */

namespace tests\AppBundle\Entity;


use AppBundle\Entity\Booking;
use AppBundle\Entity\Payment;
use AppBundle\Entity\Visitor;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PaymentTest extends WebTestCase
{
    protected $payment ;

    protected function setUp()
    {
        $this->payment = new Payment();
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
     * @param $gauge mixed
     */
    public function testPropertiesReturnSomethings($gauge)
    {
        $this->payment->setBooking($gauge);


        $this->assertContains($gauge, [$this->payment->getBooking()]);


    }
}
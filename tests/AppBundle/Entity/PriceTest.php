<?php
/**
 * Created by PhpStorm.
 * User: Garnier
 * Date: 15/08/2016
 * Time: 11:17
 */

namespace tests\AppBundle\Entity;


use AppBundle\Entity\Price;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PriceTest extends WebTestCase
{
    protected $price;

    protected function setUp()
    {
        $this->price = new Price();
    }

    public function testNameIsString()
    {
        $this->price->getId();
        $this->price->setName('wizzzz');
        $this->assertTrue(is_string($this->price->getName()));
    }

    public function testValueIsNumeric()
    {
        $this->price->setValue(1234);
        $this->assertTrue(is_numeric($this->price->getValue()));
    }

}
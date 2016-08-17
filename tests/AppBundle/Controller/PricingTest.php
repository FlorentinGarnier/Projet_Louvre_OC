<?php
/**
 * Created by PhpStorm.
 * User: Garnier
 * Date: 15/08/2016
 * Time: 14:45
 */

namespace tests\AppBundle\Controller;


use AppBundle\Entity\Booking;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PricingTest extends WebTestCase
{
    protected $client;
    protected $session;
    private $crawler;


    protected function setUp()
    {
        $this->client = static::createClient();



    }

    public function testPricingGoodResponse()
    {

        $this->crawler = $this->client->request(
            'GET',
            '/pricing',
            ['booking_nb' => 4]
        );
        $this->assertContains('Récapitulatif', $this->client->getResponse()->getContent());
    }


}
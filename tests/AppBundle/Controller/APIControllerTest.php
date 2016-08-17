<?php

namespace Tests\AppBundle\Controller;



use AppBundle\Entity\Booking;
use AppBundle\Repository\BookingRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class APIControllerTest extends WebTestCase

{

    protected $client;
    protected function setUp()
    {
        $this->client = static::createClient();
    }
    public function testpreCheckInActionWithGoodRequest()
    {

        $crawler = $this->client->request(
            'GET',
            '/preCheckIn',
            ['booking_date' => '2016-12-3'],
            [],
            ['HTTP_X-Requested-With' => 'XMLHttpRequest']
        );

        $this->assertTrue($this->client->getResponse()->isSuccessful());

        $this->assertTrue($this->client->getResponse()
            ->headers
            ->contains(
                'Content-Type',
                'application/json'
            )
        );

        $this->assertContains('1', $this->client->getResponse()->getContent());

    }

    public function testpreCheckInActionWithoutGoodRequest()
    {

        $crawler = $this->client->request(
            'GET',
            '/preCheckIn'

        );

        $this->assertTrue($this->client->getResponse()->isRedirect('/'),
            'response is redirect to homepage'
        );

    }

    public function testPreCheckInActionWhenNoPlace()
    {


        $crawler = $this->client->request(
            'GET',
            '/preCheckIn',
            ['booking_date' => '2016-12-2'],
            [],
            ['HTTP_X-Requested-With' => 'XMLHttpRequest']
        );
        $this->assertContains('0', $this->client->getResponse()->getContent());

    }
}
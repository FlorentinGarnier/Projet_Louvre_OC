<?php

namespace Tests\AppBundle\Controller;



use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class APIControllerTest extends WebTestCase

{
    public function testpreCheckInAction()
    {
        $client = static::createClient();

        $crawler = $client->request(
            'GET',
            '/preCheckIn',
            ['booking_date' => '2016-12-2'],
            [],
            ['HTTP_X-Requested-With' => 'XMLHttpRequest']
        );

        $this->assertTrue($client->getResponse()->isSuccessful());

        $this->assertTrue($client->getResponse()
            ->headers
            ->contains(
                'Content-Type',
                'application/json'
            )
        );

        $this->assertContains('status', $client->getResponse()->getContent());

    }
}
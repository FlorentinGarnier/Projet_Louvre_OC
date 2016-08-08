<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class APIControllerTest extends WebTestCase
{


    public function testAPIReturnJson()
    {
        $client = static::createClient();
        $client->request(
            'GET',
            '/preCheckin',
            [],
            [],
            [
                'HTTP_X-Requested-With' => 'XMLHttpRequest'
            ]);

        $this->assertTrue($client->getResponse()->headers->contains(
            'Content-Type',
            'application/json'
        ));

    }
}
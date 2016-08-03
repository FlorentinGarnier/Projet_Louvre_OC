<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class APIControllerControllerTest extends WebTestCase
{
    public function testIsfull()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/isFull');
    }

}

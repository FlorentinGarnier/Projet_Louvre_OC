<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PricingControllerTest extends WebTestCase
{
    public function testPricing()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/pricing');
    }

}

<?php

namespace PaymentBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    /**
     * @dataProvider urlProvider
     */
    public function testPaymentPageNotResponseWithoutSession($url)
    {
        $client = static::createClient();

        $client->request('GET', $url);

        $this->assertFalse($client->getResponse()->isSuccessful());
    }

    public function urlProvider()
    {
        return [
            ['/payment/paypal'],
            ['/payment/stripe']
        ];
    }
}

<?php

namespace TicketSystemBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function ticketResponseIsSuccess()
    {
        $client = static::createClient();

        $client->request('GET', '/sendTicket');

        $this->assertContains('ticket', $client->getResponse()->getContent());
    }
}

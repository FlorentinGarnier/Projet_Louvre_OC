<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BookingControllerTest extends WebTestCase
{


    protected $client;
    protected $form;
    protected $crawler;

    protected function setUp()
    {
        $this->client = static::createClient();
        $this->crawler = $this->client->request('GET', '/');
        $this->form = $this->crawler->selectButton('Continuer')->form();
//        $this->form['booking[visitors][0][firstName]'] = 'florentin';
//        $this->form['lastName'] = 'garnier';
//        $this->form['birthday'] = '1985-11-02';
//        $this->form['country'] = 'FR';
//        $this->form['email'] = 'garnier.florentin@gmail.com';

    }

    public function testHomePageHasGoodResponse()
    {

        $this->assertTrue($this->client->getResponse()->isSuccessful());

        $this->assertContains('<title>Musée du Louvre | Booking </title>',
            $this->client->getResponse()->getContent());
    }

    public function testPastDateReturnAFlashMessage()
    {
        $this->form['booking[visit_date]'] = '2000-01-01';
        $this->crawler = $this->client->submit($this->form);
        $this->assertContains('La date de réservation n&#039;est pas valide',
            $this->client->getResponse()->getContent());
    }

    public function testDateIsHoliday()
    {
        $this->form['booking[visit_date]'] = '2020-08-15';
        $this->crawler = $this->client->submit($this->form);
        $this->assertContains('La date de réservation n&#039;est pas valide',
            $this->client->getResponse()->getContent());
    }

}

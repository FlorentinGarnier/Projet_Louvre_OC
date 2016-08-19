<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BookingControllerTest extends WebTestCase
{


    protected $client;
    protected $form;
    protected $crawler;
    protected $value;

    protected function setUp()
    {
        $this->client = static::createClient();
        $this->crawler = $this->client->request('GET', '/');
        $this->form = $this->crawler->selectButton('Continuer')->form();
        $this->value = $this->form->getPhpValues();
        $this->value['booking']['email'] = 'garnier.florentin@gmail.com';
        $this->value['booking']['visitors']['0']['firstName'] = 'florentin';
        $this->value['booking']['visitors']['0']['lastName'] = 'garnier';
        $this->value['booking']['visitors']['0']['birthday'] = '1985-11-02';
        $this->value['booking']['visitors']['0']['country'] = 'FR';


    }

    public function testHomePageHasGoodResponse()
    {

        $this->assertTrue($this->client->getResponse()->isSuccessful());

        $this->assertContains('<title>Musée du Louvre | Booking </title>',
            $this->client->getResponse()->getContent());
    }

    public function testPastDateReturnAFlashMessage()
    {
        $this->value['booking']['visit_date'] = '2000-01-01';
        $this->crawler = $this->client->request(
            $this->form->getMethod(),
            $this->form->getUri(),
            $this->value,
            $this->form->getPhpFiles()
        );
        $this->assertContains('La date de réservation n&#039;est pas valide',
            $this->client->getResponse()->getContent());
    }

    public function testDateIsHoliday()
    {
        $this->value['booking']['visit_date'] = '2020-08-15';
        $this->crawler = $this->client->request(
            $this->form->getMethod(),
            $this->form->getUri(),
            $this->value,
            $this->form->getPhpFiles()
        );
        $this->assertContains('La date de réservation n&#039;est pas valide',
            $this->client->getResponse()->getContent());
    }

    public function testDateIsGood()
    {
        $this->value['booking']['visit_date'] = '2020-08-20';
        $this->crawler = $this->client->request(
            $this->form->getMethod(),
            $this->form->getUri(),
            $this->value,
            $this->form->getPhpFiles()
        );
        $this->assertTrue($this->client->getResponse()->isRedirect('/pricing'));
    }

    public function testDateIsTodayAndWeAreAfter2PM()
    {
        $actualTime = new \DateTime();


        if ($actualTime->format('h') >= 02) {
            $this->value['booking']['visit_date'] = $actualTime->format('Y-m-d');
            $this->crawler = $this->client->request(
                $this->form->getMethod(),
                $this->form->getUri(),
                $this->value,
                $this->form->getPhpFiles()
            );
            $this->assertContains('La date de réservation n&#039;est pas valide',
                $this->client->getResponse()->getContent());
        }
    }

    public function testIfGoodPricingIsDisplayed()
    {
        $this->value['booking']['visit_date'] = '2020-08-20';
        $this->crawler = $this->client->request(
            $this->form->getMethod(),
            $this->form->getUri(),
            $this->value,
            $this->form->getPhpFiles()
        );

        $this->crawler = $this->client->followRedirect();
        $this->assertContains('16 €',
            $this->client->getResponse()->getContent());
    }

    public function testHalfDayPricing()
    {
        $this->value['booking']['visit_date'] = '2020-08-20';
        $this->value['booking']['half_day'] = '1';
        $this->crawler = $this->client->request(
            $this->form->getMethod(),
            $this->form->getUri(),
            $this->value,
            $this->form->getPhpFiles()
        );

        $this->crawler = $this->client->followRedirect();
        $this->assertContains('8 €',
            $this->client->getResponse()->getContent());
    }

    public function testFamilyPricing()
    {   $this->value['booking']['visitors']['1']['firstName'] = 'Émilie';
        $this->value['booking']['visitors']['1']['lastName'] = 'garnier';
        $this->value['booking']['visitors']['1']['birthday'] = '1983-08-25';
        $this->value['booking']['visitors']['1']['country'] = 'FR';

        $this->value['booking']['visitors']['2']['firstName'] = 'Raphael';
        $this->value['booking']['visitors']['2']['lastName'] = 'garnier';
        $this->value['booking']['visitors']['2']['birthday'] = '2008-11-02';
        $this->value['booking']['visitors']['2']['country'] = 'FR';

        $this->value['booking']['visitors']['3']['firstName'] = 'Mélissa';
        $this->value['booking']['visitors']['3']['lastName'] = 'garnier';
        $this->value['booking']['visitors']['3']['birthday'] = '2008-11-02';
        $this->value['booking']['visitors']['3']['country'] = 'FR';
        $this->value['booking']['visit_date'] = '2020-08-20';
        $this->crawler = $this->client->request(
            $this->form->getMethod(),
            $this->form->getUri(),
            $this->value,
            $this->form->getPhpFiles()
        );



        $this->crawler = $this->client->followRedirect();
        $this->assertContains('35 €',
            $this->client->getResponse()->getContent());
    }

    public function testSeniorPrice()
    {
        $this->value['booking']['visitors']['0']['birthday'] = '1950-11-02';
        $this->value['booking']['visit_date'] = '2020-08-20';
        $this->crawler = $this->client->request(
            $this->form->getMethod(),
            $this->form->getUri(),
            $this->value,
            $this->form->getPhpFiles()
        );


        $this->crawler = $this->client->followRedirect();
        $this->assertContains('12 €',
            $this->client->getResponse()->getContent());

    }

    public function testReducePrice()
    {
        $this->value['booking']['visit_date'] = '2020-08-20';
        $this->value['booking']['visitors']['0']['reduce'] = '1';
        $this->crawler = $this->client->request(
            $this->form->getMethod(),
            $this->form->getUri(),
            $this->value,
            $this->form->getPhpFiles()
        );

        $this->crawler = $this->client->followRedirect();
        $this->assertContains('10 €',
            $this->client->getResponse()->getContent());
    }

    public function testLessThan4YearsPriceReturn0Euros()
    {
        $this->value['booking']['visitors']['0']['birthday'] = '2014-11-02';
        $this->value['booking']['visit_date'] = '2020-08-20';
        $this->crawler = $this->client->request(
            $this->form->getMethod(),
            $this->form->getUri(),
            $this->value,
            $this->form->getPhpFiles()
        );

        $this->crawler = $this->client->followRedirect();
        $this->assertContains('0 €',
            $this->client->getResponse()->getContent());

    }

    public function testPaymentWithStripe()
    {
        $this->value['booking']['visitors']['1']['firstName'] = 'Émilie';
        $this->value['booking']['visitors']['1']['lastName'] = 'garnier';
        $this->value['booking']['visitors']['1']['birthday'] = '1983-08-25';
        $this->value['booking']['visitors']['1']['country'] = 'FR';

        $this->value['booking']['visitors']['2']['firstName'] = 'Raphael';
        $this->value['booking']['visitors']['2']['lastName'] = 'garnier';
        $this->value['booking']['visitors']['2']['birthday'] = '2008-11-02';
        $this->value['booking']['visitors']['2']['country'] = 'FR';

        $this->value['booking']['visitors']['3']['firstName'] = 'Mélissa';
        $this->value['booking']['visitors']['3']['lastName'] = 'garnier';
        $this->value['booking']['visitors']['3']['birthday'] = '2008-11-02';
        $this->value['booking']['visitors']['3']['country'] = 'FR';
        $this->value['booking']['visit_date'] = '2020-08-20';
        $this->crawler = $this->client->request(
            $this->form->getMethod(),
            $this->form->getUri(),
            $this->value,
            $this->form->getPhpFiles()
        );



        $this->crawler = $this->client->followRedirect();
        $this->assertContains('35 €',
            $this->client->getResponse()->getContent());

        $link = $this->crawler->selectLink('Paiement CB')->link();

        $this->client->click($link);
    }


}

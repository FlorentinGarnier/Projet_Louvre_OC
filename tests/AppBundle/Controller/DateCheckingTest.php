<?php
/**
 * Created by PhpStorm.
 * User: Garnier
 * Date: 07/08/2016
 * Time: 19:50
 */

namespace tests\AppBundle\Controller;


use AppBundle\DateChecking\DateChecking;
use Symfony\Bundle\FrameworkBundle\Tests\Functional\WebTestCase;

class DateCheckingTest extends WebTestCase
{
    private $DATE = '25-09-2020';
    /**
     * @param $ts
     * @dataProvider tsProvider
     */
    public function testDateCheckingReturnFalse($ts)
    {
        $dateChecking = new DateChecking();
        $this->assertFalse($dateChecking->isValid($ts));

    }

    /**
     * Test Ultra Bancale!!!!
     */
    public function testDateCheckingReturnTrue()
    {
        $dateChecking =new DateChecking();

        $this->assertTrue($dateChecking->isValid(strtotime($this->DATE)));

    }

    public function tsProvider()
    {

        $year = date('Y')+1;
        $easter = easter_date($year);

        return [
            [strtotime('01-01-' . $year)],
            [strtotime('01-05-' . $year)],
            [strtotime('08-05-' . $year)],
            [strtotime('14-07-' . $year)],
            [strtotime('15-08-' . $year)],
            [strtotime('11-11-' . $year)],
            [strtotime('25-12-' . $year)],
            [strtotime('Tue')],
            [strtotime('Sun')],
            [$easter + 86400],
            [$easter + 3369600],
            [$easter + 4320000]



        ];
    }
}
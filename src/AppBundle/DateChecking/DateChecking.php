<?php
/**
 * Created by PhpStorm.
 * User: Garnier
 * Date: 27/07/2016
 * Time: 11:29
 */

namespace AppBundle\DateChecking;

class DateChecking
{


    /**
     * Check if date is an holliday for the museum
     * Take a timestamp in parameter
     *
     * @param $ts
     * @return bool
     */
    public function isValid($ts, $halfDay)
    {
        // Licence : Creative Commons (BY)
        // By Webpulser - http://goo.gl/76t31
        $fixed_holidays = ['01-01', '01-05', '08-05', '14-07', '15-08', '11-11', '25-12'];
        $format = 'd-m';
        $today = new \DateTime();
        $todayTs = $today->getTimestamp();

        // Check if the visit date is today and not possibility to proceed for a day ticket after 2 PM
        if ($halfDay == false){

            if ((date('Y-m-d',$ts) == date('Y-m-d',$todayTs)) && ($today->format('h') >= 02)) return false;

        }

        //check if the day is yesterday
        if ($ts-($todayTs - 86400) < 0) return false;

        //check if the day is a musuem holiday
        $dm = date($format, $ts);
        if (date('D', $ts) ==='Tue' || date('D', $ts) === 'Sun') return false;
        if (in_array($dm, $fixed_holidays)) return false;

        //check date for christian holiday
        $easter = easter_date(date('Y', $ts));
        if (date($format, $easter + 86400) == $dm) return false;
        if (date($format, $easter + 3369600) == $dm) return false;
        if (date($format, $easter + 4320000) == $dm) return false;



        return true;
    }


}


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
    public function isValid($ts)
    {
        // Licence : Creative Commons (BY)
        // By Webpulser - http://goo.gl/76t31
        $fixed_holidays = ['01-01', '01-05', '08-05', '14-07', '15-08', '11-11', '25-12'];
        $format = 'd-m';

        $dm = date($format, $ts);
        if (date('D', $ts) ==='tue' || date('D', $ts) === 'sun') return false;
        if (in_array($dm, $fixed_holidays)) return false;

        $easter = easter_date(date('Y', $ts));
        if (date($format, $easter + 86400) == $dm) return false;
        if (date($format, $easter + 3369600) == $dm) return false;
        if (date($format, $easter + 4320000) == $dm) return false;

        return true;
    }
}
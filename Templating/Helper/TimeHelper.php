<?php

namespace Bundle\TimeBundle\Templating\Helper;

use Symfony\Component\Templating\Helper\Helper;
use DateTime;

class TimeHelper extends Helper
{
    /**
     * Returns a single number of years, months, days, hours, minutes or seconds between the current date and the provided date.
     * If the date occurs in the past (is negative/inverted), it suffixes it with 'ago'.
     *
     * @param  mixed $since The datetime for wich the diff will be calculated
     * @param  mixed $to    Tho datetime from wich the diff will be calculated
     *
     * @return string
     */
    public function ago($since = null, $to = null)
    {
        if (null === $since) {
            return '';
        }

        $since = $this->getDatetimeObject($since);
        $to = $this->getDatetimeObject($to);

        $interval = $to->diff($since);

        if (0 !== $interval->y) {
            $count  = $interval->y;
            $unit   = 'year';
        }
        elseif (0 !== $interval->m) {
            $count  = $interval->m;
            $unit   = 'month';
        }
        elseif (0 !== $interval->d) {
            $count  = $interval->d;
            $unit   = 'day';
        }
        elseif (0 !== $interval->h) {
            $count  = $interval->h;
            $unit   = 'hour';
        }
        elseif (0 !== $interval->i) {
            $count  = $interval->i;
            $unit   = 'minute';
        }
        elseif (0 !== $interval->s) {
            $count  = $interval->s;
            $unit   = 'second';
        }
        else {
            return 'just now';
        }

        return sprintf('%s %s ago', $count, $this->pluralize($count, $unit));
    }

    /**
     * Returns the pluralized version of the given text depending of the
     * specified count
     *
     * @param  integer $count
     * @param  string  $text
     */
    public function pluralize($count, $text)
    {
        return $count === 1 ? $text : $text . 's';
    }

    /**
     * Returns a DateTime instance for the given datetime
     *
     * @param  mixed $datetime
     *
     * @return DateTime
     */
    public function getDatetimeObject($datetime = null)
    {
        if ($datetime instanceof DateTime) {
            return $datetime;
        }

        if (is_integer($datetime)) {
            $datetime = date('Y-m-d H:i:s', $datetime);
        }

        return new DateTime($datetime);
    }

    public function getName()
    {
        return 'time';
    }
}

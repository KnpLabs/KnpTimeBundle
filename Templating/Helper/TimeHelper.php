<?php

namespace Bundle\TimeBundle\Templating\Helper;

use Symfony\Component\Templating\Helper\HelperInterface;
use DateTime;

class TimeHelper implements HelperInterface
{
    /**
     * Returns a single number of years, months, days, hours, minutes or seconds between the current date and the provided date.
     * If the date occurs in the past (is negative/inverted), it suffixes it with 'ago'.
     *
     * @return string
     **/
    public function ago(DateTime $since = null, DateTime $to = null)
    {
        if(!$since) return '';

        $to = $to ?: new DateTime();
        $interval = $to->diff($since);
        $suffix = ( $interval->invert ? ' ago' : '' );
        if ( $v = $interval->y >= 1 ) return static::pluralize( $interval->y, 'year' ) . $suffix;
        if ( $v = $interval->m >= 1 ) return static::pluralize( $interval->m, 'month' ) . $suffix;
        if ( $v = $interval->d >= 1 ) return static::pluralize( $interval->d, 'day' ) . $suffix;
        if ( $v = $interval->h >= 1 ) return static::pluralize( $interval->h, 'hour' ) . $suffix;
        if ( $v = $interval->i >= 1 ) return static::pluralize( $interval->i, 'minute' ) . $suffix;
        return static::pluralize( $interval->s, 'second' ) . $suffix;
    }

    protected static function pluralize($count, $text)
    {
        return $count.' '.(  $count === 1  ?  $text  :  $text.'s'  );
    }

    public function getName()
    {
        return 'time';
    }
}

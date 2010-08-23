<?php

namespace Bundle\TimeBundle\Helper;

use Symfony\Components\Templating\Helper\HelperInterface;

class TimeHelper implements HelperInterface
{
    /**
     * @var TimeParser
     */
    protected $parser;
    protected $charset = 'UTF-8';

    /**
     * Sets the default charset.
     *
     * @param string $charset The charset
     */
    public function setCharset($charset)
    {
        $this->charset = $charset;
    }

    /**
     * Gets the default charset.
     *
     * @return string The default charset
     */
    public function getCharset()
    {
        return $this->charset;
    }

    public function getName()
    {
        return 'time';
    }

    /**
     * Returns a single number of years, months, days, hours, minutes or seconds between the current date and the provided date.
     * If the date occurs in the past (is negative/inverted), it suffixes it with 'ago'.
     *
     * @return string
     **/
    public function ago(\DateTime $since, \DateTime $to = null)
    {
        $to = $to ?: new \DateTime();
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
        return $count.' '.( ( $count === 1 ) ? ( $text ) : ( $text.'s' ) );
    }

    /**
     * Transforms markdown syntax to HTML
     * @param   string  $markdownText   The markdown syntax text
     * @return  string                  The HTML code
     */
    public function transform($markdownText)
    {
        return $this->parser->transform($markdownText);
    }

}

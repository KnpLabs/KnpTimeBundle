<?php

namespace Knp\Bundle\TimeBundle\Templating\Helper;

use Symfony\Component\Templating\Helper\Helper;
use Knp\Bundle\TimeBundle\DateTimeFormatter;

class TimeHelper extends Helper
{
    protected $formatter;

    public function __construct(DateTimeFormatter $formatter)
    {
        $this->formatter = $formatter;
    }

    /**
     * Returns a single number of years, months, days, hours, minutes or
     * seconds between the specified date times.
     *
     * @param  mixed $since The datetime for which the diff will be calculated
     * @param  mixed $since The datetime from which the diff will be calculated
     *
     * @return string
     */
    public function diff($from, $to = null)
    {
        $from = $this->formatter->getDatetimeObject($from);
        $to = $this->formatter->getDatetimeObject($to);

        return $this->formatter->formatDiff($from, $to);
    }

    /**
     * @deprecated Use DateTimeFormatter::getDateTimeObject() directly.
     */
    public function getDatetimeObject($datetime = null)
    {
        return $this->formatter->getDatetimeObject($datetime);
    }

    public function getName(): string
    {
        return 'time';
    }
}

<?php

namespace Knp\Bundle\TimeBundle\Templating\Helper;

use Symfony\Component\Templating\Helper\Helper;
use Knp\Bundle\TimeBundle\DateTimeFormatter;
use DateTime;

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
        $from = $this->getDatetimeObject($from);
        $to = $this->getDatetimeObject($to);

        return $this->formatter->formatDiff($from, $to);
    }

    /**
     * Returns a single number of full years
     * between the specified date times.
     *
     * @param  mixed $birthdate The datetime for which the diff will be calculated
     * @param  mixed $current The datetime from which the diff will be calculated
     *
     * @return string
     */
    public function age($birthdate, $current = null)
    {
        $birthdate = $this->getDatetimeObject($birthdate);
        $current = $this->getDatetimeObject($current);

        return $this->formatter->formatAge($birthdate, $current);
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

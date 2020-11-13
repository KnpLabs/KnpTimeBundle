<?php

namespace Knp\Bundle\TimeBundle;

use Symfony\Contracts\Translation\TranslatorInterface;
use DateTime;
use DatetimeInterface;
use Carbon\Carbon;

class DateTimeFormatter
{
    protected $translator;

    /**
     * Constructor
     *
     * @param  TranslatorInterface $translator Translator used for messages
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * Returns a formatted diff for the given from and to datetimes
     *
     * @param  DateTimeInterface $from
     * @param  DateTimeInterface $to
     *
     * @return string
     */
    public function formatDiff(DateTimeInterface $from, DateTimeInterface $to)
    {
        $start = Carbon::parse($from);
        $end = Carbon::parse($to);
        
        return $end->diffForHumans($start);
    }

    /**
     * Returns the diff message for the specified count and unit
     *
     * @param  integer $count  The diff count
     * @param  boolean $invert Whether to invert the count
     * @param  integer $unit   The unit must be either year, month, day, hour,
     *                         minute or second
     *
     * @return string
     */
    public function getDiffMessage($count, $invert, $unit)
    {
        if (0 === $count) {
            throw new \InvalidArgumentException('The count must not be null.');
        }

        $unit = strtolower($unit);

        if (!in_array($unit, array('year', 'month', 'day', 'hour', 'minute', 'second'))) {
            throw new \InvalidArgumentException(sprintf('The unit \'%s\' is not supported.', $unit));
        }

        return $this->doGetDiffMessage($count, $invert, $unit);
    }

    /**
     * Returns a DateTime instance for the given datetime
     *
     * @param mixed $datetime
     */
    public function getDateTimeObject($dateTime = null): DateTimeInterface
    {
        if ($dateTime instanceof DateTimeInterface) {
            return $dateTime;
        }

        if (is_int($dateTime)) {
            $dateTime = date('Y-m-d H:i:s', $dateTime);
        }

        return new DateTime($dateTime);
    }

    protected function doGetDiffMessage($count, $invert, $unit)
    {
        $id = sprintf('diff.%s.%s', $invert ? 'ago' : 'in', $unit);

        // check for Symfony >= 4.2
        if (class_exists('Symfony\Component\Translation\Formatter\IntlFormatter')) {
            return $this->translator->trans($id, array('%count%' => $count), 'time');
        } else {
            return $this->translator->transChoice($id, $count, array('%count%' => $count), 'time');
        }
    }

    /**
     * Returns the message for an empty diff
     *
     * @return string
     */
    public function getEmptyDiffMessage()
    {
        return $this->translator->trans('diff.empty', array(), 'time');
    }
}

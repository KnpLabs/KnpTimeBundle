<?php

namespace Knp\Bundle\TimeBundle;

use Symfony\Contracts\Translation\TranslatorInterface;
use DateTime;
use DatetimeInterface;

class DateTimeFormatter
{
    protected $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * Returns a formatted diff for the given from and to datetimes
     */
    public function formatDiff(DateTimeInterface $from, DateTimeInterface $to = null, string $locale = null): string
    {
        if (null === $to) {
            $to = new \DateTimeImmutable('now');
        }

        static $units = array(
            'y' => 'year',
            'm' => 'month',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second'
        );

        $diff = $to->diff($from);

        foreach ($units as $attribute => $unit) {
            $count = $diff->$attribute;
            if (0 !== $count) {
                return $this->doGetDiffMessage($count, $diff->invert, $unit, $locale);
            }
        }

        return $this->getEmptyDiffMessage($locale);
    }

    /**
     * Returns the diff message for the specified count and unit
     *
     * @param  integer $count  The diff count
     * @param  boolean $invert Whether to invert the count
     * @param  string $unit   The unit must be either year, month, day, hour,
     *                         minute or second
     */
    public function getDiffMessage(int $count, bool $invert, string $unit): string
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
     * @param mixed $dateTime
     */
    public function getDateTimeObject($dateTime = null): DateTimeInterface
    {
        if ($dateTime instanceof DateTimeInterface) {
            return $dateTime;
        }

        if (is_int($dateTime)) {
            $dateTime = date('Y-m-d H:i:s', $dateTime);
        }

        if (null === $dateTime) {
            $dateTime = 'now';
        }

        return new DateTime($dateTime);
    }

    protected function doGetDiffMessage(int $count, bool $invert, string $unit, string $locale = null): string
    {
        $id = sprintf('diff.%s.%s', $invert ? 'ago' : 'in', $unit);

        return $this->translator->trans($id, array('%count%' => $count), 'time', $locale);
    }

    /**
     * Returns the message for an empty diff
     */
    public function getEmptyDiffMessage(string $locale = null): string
    {
        return $this->translator->trans('diff.empty', array(), 'time', $locale);
    }
}

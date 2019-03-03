<?php

namespace Knp\Bundle\TimeBundle;

use DateInterval;
use DateTime;
use Symfony\Component\Translation\TranslatorInterface;
use DatetimeInterface;

class DateTimeFormatter
{
    protected $translator;
    
    protected $maxDiff;
    
    protected $maxDiffUnit;
    
    protected $dateFormat;

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
        static $units = array(
            'y' => 'year',
            'm' => 'month',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second'
        );

        $diff = $to->diff($from);
        
        if ($this->maxDiff && $this->maxDiffUnit && $this->dateFormat) {
            //We have the "maxDiff" options set
            $timeIntervalUnits = [
                'hour' => 'H',
                'minute' => 'M',
                'second' => 'S',
            ];
            $intervalUnits = [
                    'year' => 'Y',
                    'month' => 'M',
                    'day' => 'D',
                ] + $timeIntervalUnits;
            
            if (!array_key_exists($this->maxDiffUnit, $intervalUnits)) {
                throw new \InvalidArgumentException(sprintf('The unit \'%s\' is not supported.', $this->maxDiffUnit));
            }
            
            //We create the interval format
            $formatInterval = 'P';
            $formatInterval .= (array_key_exists($this->maxDiffUnit, $timeIntervalUnits)?'T':'');
            $formatInterval .= $this->maxDiff . $intervalUnits[$this->maxDiffUnit];
            
            if ($diff->invert) {
                //With the interval format we create the "maxDateTime"
                $maxDiffDateTime = (clone $to)->sub(new DateInterval($formatInterval));
                
                //The tested DateTime is older than the "maxDateTime", we display the date with the passed format
                if ($maxDiffDateTime > $from)
                {
                    return $from->format($this->dateFormat);
                }
            }
            else {
                //With the interval format we create the "maxDateTime"
                $maxDiffDateTime = (clone $to)->add(new DateInterval($formatInterval));
                //The tested DateTime is "newer" than the "maxDateTime", we display the date with the passed format
                if ($maxDiffDateTime < $from)
                {
                    return $from->format($this->dateFormat);
                }
            }
        }
        
        foreach ($units as $attribute => $unit) {
            $count = $diff->$attribute;
            if (0 !== $count) {
                return $this->doGetDiffMessage($count, $diff->invert, $unit);
            }
        }

        return $this->getEmptyDiffMessage();
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

    protected function doGetDiffMessage($count, $invert, $unit)
    {
        $id = sprintf('diff.%s.%s', $invert ? 'ago' : 'in', $unit);

        return $this->translator->trans($id, array('%count%' => $count), 'time');
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

    /**
     * @param mixed $maxDiff
     */
    public function setMaxDiff($maxDiff)
    {
        $this->maxDiff = $maxDiff;
    }
    
    /**
     * @param mixed $maxDiffUnit
     */
    public function setMaxDiffUnit($maxDiffUnit)
    {
        $this->maxDiffUnit = $maxDiffUnit;
    }
    
    /**
     * @param mixed $dateFormat
     */
    public function setDateFormat($dateFormat)
    {
        $this->dateFormat = $dateFormat;
    }
    
}

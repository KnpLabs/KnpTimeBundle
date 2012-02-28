<?php

namespace Knp\Bundle\TimeBundle;

use Symfony\Component\Translation\TranslatorInterface;
use Datetime;

class DateTimeFormatter
{
    protected $translator;

    // Set age locales
    // FORMAT: locale => group key
    protected $ageConfigLocales = array(
        'ru'    => 'ru_group',  // Russia
        'be'    => 'ru_group',  // Belarus
        // add another locales if need
    );

    // Set age configuration
    // FORMAT: group key => [ template => translator key for string suffix ]
    protected $ageConfigGroups = array(
        // default group, mandatory
        'default' => array(
            '~' => 'years', // default value, mandatory
            '1' => 'year',  // template (value or ~N construction, e.g. `~13` matches 7613, 13, etc.) | excluded numbers
        ),
        // custom group
        'ru_group' => array(
            '~'                 => 'years',
            '~1|11'             => 'year',
            '~2,~3,~4|12,13,14' => 'year_',
        ),
        // add another groups if need
    );

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
     * @param  Datetime $from
     * @param  Datetime $to
     *
     * @return string
     */
    public function formatDiff(Datetime $from, Datetime $to)
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

        return $this->translator->transChoice($id, $count, array('%count%' => $count), 'time');
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
     * Returns a formatted diff for the given birthdate and current datetimes
     *
     * @param  Datetime $birthdate
     * @param  Datetime $current
     *
     * @return string
     */
    public function formatAge(Datetime $birthdate, Datetime $current)
    {
        $locale = $this->translator->getLocale();
        $config = $this->ageConfigGroups['default'];

        if (isset($this->ageConfigLocales[$locale]) &&
            isset($this->ageConfigGroups[$this->ageConfigLocales[$locale]])) {
            $config = $this->ageConfigGroups[$this->ageConfigLocales[$locale]];
        }

        $yearsDiff = $birthdate->diff($current);

        if ($yearsDiff->invert) {
            return $this->getIncorrectAgeMessage();
        }

        // prepare variables
        $y = $yearsDiff->y;
        $yearsMinimized = $y;

        if ($yearsMinimized > 100) {
            $yearsMinimized = $yearsMinimized%100;
        }

        $yearsSuffix = $config['~'];
        $yearsMinimizedLength = strlen($yearsMinimized);

        // get years suffix
        foreach ($config as $k => $v) {
            if ($k == '~') continue;
            $rules = explode('|', $k);
            if (isset($rules[1])) {
                $excluded = explode(',', $rules[1]);
                if (in_array($yearsMinimized, $excluded)) continue;
            }
            $used = explode(',', $rules[0]);
            if (in_array($yearsMinimized, $used)) {
                $yearsSuffix = $v;
                break;
            }
            foreach ($used as $tpl) {
                $tpl = ltrim($tpl, '~');
                if (strrpos($yearsMinimized, $tpl) === $yearsMinimizedLength - strlen($tpl)) {
                    $yearsSuffix = $v;
                    break;
                }
            }
        }

        return $this->getAgeMessage($y, $yearsSuffix);
    }

    /**
     * @param $years
     * @param $key
     *
     * @return string
     */
    protected function getAgeMessage($years, $key)
    {
        return $years . ' ' . $this->translator->trans('knp_time.text.age_suffix.' . $key);
    }

    /**
     * @return string
     */
    public function getIncorrectAgeMessage()
    {
        return $this->translator->trans('knp_time.text.age_incorrect');
    }
}

<?php

namespace Knp\Bundle\TimeBundle;

use Symfony\Contracts\Translation\TranslatorInterface;

final class DateTimeFormatter
{
    /**
     * @internal
     */
    public function __construct(private TranslatorInterface $translator)
    {
    }

    /**
     * Returns a formatted diff for the given from and to datetimes.
     */
    public function formatDiff(
        int|string|\DateTimeInterface $from,
        int|string|\DateTimeInterface|null $to = null,
        ?string $locale = null
    ): string {
        $from = self::formatDateTime($from);
        $to = self::formatDateTime($to);

        static $units = [
            'y' => 'year',
            'm' => 'month',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second',
        ];

        $diff = $to->diff($from);

        foreach ($units as $attribute => $unit) {
            $count = $diff->$attribute;

            if (0 !== $count) {
                $id = sprintf('diff.%s.%s', $diff->invert ? 'ago' : 'in', $unit);

                return $this->translator->trans($id, ['%count%' => $count], 'time', $locale);
            }
        }

        return $this->translator->trans('diff.empty', [], 'time', $locale);
    }

    /**
     * @author Fabien Potencier <fabien@symfony.com>
     *
     * @source https://github.com/symfony/symfony/blob/ad72245261792c6b5d2db821fcbd141b11095215/src/Symfony/Component/Console/Helper/Helper.php#L97
     */
    public function formatDuration(float $seconds, ?string $locale = null): string
    {
        static $timeFormats = [
            [0, 'duration.none'],
            [1, 'duration.second'],
            [2, 'duration.second', 1],
            [60, 'duration.minute'],
            [120, 'duration.minute', 60],
            [3600, 'duration.hour'],
            [7200, 'duration.hour', 3600],
            [86400, 'duration.day'],
            [172800, 'duration.day', 86400],
        ];

        foreach ($timeFormats as $index => $format) {
            if ($seconds >= $format[0]) {
                if ((isset($timeFormats[$index + 1]) && $seconds < $timeFormats[$index + 1][0])
                    || $index === \count($timeFormats) - 1
                ) {
                    if (2 === \count($format)) {
                        return $this->translator->trans($format[1], ['%count%' => 1], 'time', $locale);
                    }

                    return $this->translator->trans(
                        $format[1],
                        ['%count%' => floor($seconds / $format[2])],
                        'time',
                        $locale
                    );
                }
            }
        }

        return $this->translator->trans('duration.none', [], 'time', $locale);
    }

    /**
     * Returns a formatted age for the given from and to datetimes.
     */
    public function formatAge(
        int|string|\DateTimeInterface $from,
        int|string|\DateTimeInterface|null $to = null,
        ?string $locale = null
    ): string {
        $from = self::formatDateTime($from);
        $to = self::formatDateTime($to);

        $diff = $from->diff($to);

        return $this->translator->trans('age', ['%count%' => $diff->y], 'time', $locale);
    }

    private static function formatDateTime(int|string|\DateTimeInterface|null $value): \DateTimeInterface
    {
        if ($value instanceof \DateTimeInterface) {
            return $value;
        }

        if (is_int($value)) {
            $value = date('Y-m-d H:i:s', $value);
        }

        if (null === $value) {
            $value = 'now';
        }

        return new \DateTime($value);
    }
}

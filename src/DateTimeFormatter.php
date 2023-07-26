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
        int|string|\DateTimeInterface $to = null,
        string $locale = null
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

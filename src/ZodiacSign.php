<?php

namespace Knp\Bundle\TimeBundle;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum ZodiacSign: string implements TranslatableInterface
{
    case Aries = 'aries';
    case Taurus = 'taurus';
    case Gemini = 'gemini';
    case Cancer = 'cancer';
    case Leo = 'leo';
    case Virgo = 'virgo';
    case Libra = 'libra';
    case Scorpio = 'scorpio';
    case Sagittarius = 'sagittarius';
    case Capricorn = 'capricorn';
    case Aquarius = 'aquarius';
    case Pisces = 'pisces';

    public function trans(TranslatorInterface $translator, ?string $locale = null): string
    {
        return $translator->trans('zodiac_sign.'.$this->value, domain: 'zodiac_sign', locale: $locale);
    }

    public function getSymbol(): string
    {
        return match ($this) {
            self::Aries => '♈',
            self::Taurus => '♉',
            self::Gemini => '♊',
            self::Cancer => '♋',
            self::Leo => '♌',
            self::Virgo => '♍',
            self::Libra => '♎',
            self::Scorpio => '♏',
            self::Sagittarius => '♐',
            self::Capricorn => '♑',
            self::Aquarius => '♒',
            self::Pisces => '♓',
        };
    }

    public function contains(\DateTimeImmutable $date): bool
    {
        $normalized = \DateTimeImmutable::createFromFormat('!m-d', $date->format('m-d'), new \DateTimeZone('UTC'));
        $start = \DateTimeImmutable::createFromFormat('!m-d', $this->getDateRange()['start'], new \DateTimeZone('UTC'));
        $end = \DateTimeImmutable::createFromFormat('!m-d', $this->getDateRange()['end'], new \DateTimeZone('UTC'));

        if (self::Capricorn === $this) {
            return $normalized >= $start || $normalized <= $end;
        }

        return $normalized >= $start && $normalized <= $end;
    }

    /** @return array{start: string, end: string} */
    private function getDateRange(): array
    {
        return match ($this) {
            self::Aquarius => ['start' => '01-20', 'end' => '02-18'],
            self::Pisces => ['start' => '02-19', 'end' => '03-20'],
            self::Aries => ['start' => '03-21', 'end' => '04-19'],
            self::Taurus => ['start' => '04-20', 'end' => '05-20'],
            self::Gemini => ['start' => '05-21', 'end' => '06-20'],
            self::Cancer => ['start' => '06-21', 'end' => '07-22'],
            self::Leo => ['start' => '07-23', 'end' => '08-22'],
            self::Virgo => ['start' => '08-23', 'end' => '09-22'],
            self::Libra => ['start' => '09-23', 'end' => '10-22'],
            self::Scorpio => ['start' => '10-23', 'end' => '11-21'],
            self::Sagittarius => ['start' => '11-22', 'end' => '12-21'],
            self::Capricorn => ['start' => '12-22', 'end' => '01-19'],
        };
    }
}

<?php

namespace Knp\Bundle\TimeBundle\Tests;

use Knp\Bundle\TimeBundle\DateTimeFormatter;
use Knp\Bundle\TimeBundle\ZodiacSign;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\Translation\TranslatorInterface;

final class ZodiacSignCalculatorTest extends TestCase
{
    private DateTimeFormatter $formatter;

    protected function setUp(): void
    {
        $translator = $this->getMockBuilder(TranslatorInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->formatter = new DateTimeFormatter($translator);
    }

    /**
     * @dataProvider zodiacSignProvider
     */
    public function testCalculateZodiacSign(string $date, ZodiacSign $expectedSign): void
    {
        $inputs = [
            'string' => $date,
            'DateTime' => new \DateTime($date),
            'DateTimeImmutable' => new \DateTimeImmutable($date),
        ];

        foreach ($inputs as $type => $input) {
            $result = $this->formatter->calculateZodiacSign($input);
            $this->assertSame($expectedSign, $result, \sprintf('Failed for input type: %s', $type));
        }
    }

    /**
     * @dataProvider zodiacSignTimestampProvider
     */
    public function testCalculateZodiacSignFromTimestamp(int $timestamp, ZodiacSign $expectedSign): void
    {
        $this->assertSame($expectedSign, $this->formatter->calculateZodiacSign($timestamp));
    }

    public function testCalculateZodiacSignFromFloat(): void
    {
        $this->assertSame(ZodiacSign::Aries, $this->formatter->calculateZodiacSign(954028800.5));
    }

    public function testCalculateZodiacSignWithNullDefaultsToNow(): void
    {
        $this->assertContains($this->formatter->calculateZodiacSign(null), ZodiacSign::cases());
    }

    public function testCalculateZodiacSignWithoutParameterDefaultsToNow(): void
    {
        $this->assertContains($this->formatter->calculateZodiacSign(), ZodiacSign::cases());
    }

    public function testCalculateZodiacSignWithInvalidDateThrows(): void
    {
        $this->expectException(\Exception::class);
        $this->formatter->calculateZodiacSign('not-a-date');
    }

    public function testBoundaryDates(): void
    {
        $this->assertSame(ZodiacSign::Pisces, $this->formatter->calculateZodiacSign('2000-03-20'));
        $this->assertSame(ZodiacSign::Aries, $this->formatter->calculateZodiacSign('2000-03-21'));
        $this->assertSame(ZodiacSign::Aries, $this->formatter->calculateZodiacSign('2000-04-19'));
        $this->assertSame(ZodiacSign::Taurus, $this->formatter->calculateZodiacSign('2000-04-20'));
    }

    public function testLeapYearDate(): void
    {
        $this->assertSame(ZodiacSign::Pisces, $this->formatter->calculateZodiacSign('2000-02-29'));
    }

    public function testSameSignAcrossDifferentYears(): void
    {
        $this->assertSame(ZodiacSign::Leo, $this->formatter->calculateZodiacSign('1990-08-01'));
        $this->assertSame(ZodiacSign::Leo, $this->formatter->calculateZodiacSign('2000-08-01'));
        $this->assertSame(ZodiacSign::Leo, $this->formatter->calculateZodiacSign('2050-08-01'));
    }

    public static function zodiacSignProvider(): array
    {
        return [
            'Aries - start (March 21)' => ['2000-03-21', ZodiacSign::Aries],
            'Aries - middle (April 1)' => ['2000-04-01', ZodiacSign::Aries],
            'Aries - end (April 19)' => ['2000-04-19', ZodiacSign::Aries],

            'Taurus - start (April 20)' => ['2000-04-20', ZodiacSign::Taurus],
            'Taurus - middle (May 1)' => ['2000-05-01', ZodiacSign::Taurus],
            'Taurus - end (May 20)' => ['2000-05-20', ZodiacSign::Taurus],

            'Gemini - start (May 21)' => ['2000-05-21', ZodiacSign::Gemini],
            'Gemini - middle (June 1)' => ['2000-06-01', ZodiacSign::Gemini],
            'Gemini - end (June 20)' => ['2000-06-20', ZodiacSign::Gemini],

            'Cancer - start (June 21)' => ['2000-06-21', ZodiacSign::Cancer],
            'Cancer - middle (July 1)' => ['2000-07-01', ZodiacSign::Cancer],
            'Cancer - end (July 22)' => ['2000-07-22', ZodiacSign::Cancer],

            'Leo - start (July 23)' => ['2000-07-23', ZodiacSign::Leo],
            'Leo - middle (August 1)' => ['2000-08-01', ZodiacSign::Leo],
            'Leo - end (August 22)' => ['2000-08-22', ZodiacSign::Leo],

            'Virgo - start (August 23)' => ['2000-08-23', ZodiacSign::Virgo],
            'Virgo - middle (September 1)' => ['2000-09-01', ZodiacSign::Virgo],
            'Virgo - end (September 22)' => ['2000-09-22', ZodiacSign::Virgo],

            'Libra - start (September 23)' => ['2000-09-23', ZodiacSign::Libra],
            'Libra - middle (October 1)' => ['2000-10-01', ZodiacSign::Libra],
            'Libra - end (October 22)' => ['2000-10-22', ZodiacSign::Libra],

            'Scorpio - start (October 23)' => ['2000-10-23', ZodiacSign::Scorpio],
            'Scorpio - middle (November 1)' => ['2000-11-01', ZodiacSign::Scorpio],
            'Scorpio - end (November 21)' => ['2000-11-21', ZodiacSign::Scorpio],

            'Sagittarius - start (November 22)' => ['2000-11-22', ZodiacSign::Sagittarius],
            'Sagittarius - middle (December 1)' => ['2000-12-01', ZodiacSign::Sagittarius],
            'Sagittarius - end (December 21)' => ['2000-12-21', ZodiacSign::Sagittarius],

            'Capricorn - start (December 22)' => ['2000-12-22', ZodiacSign::Capricorn],
            'Capricorn - middle (January 1)' => ['2000-01-01', ZodiacSign::Capricorn],
            'Capricorn - end (January 19)' => ['2000-01-19', ZodiacSign::Capricorn],

            'Aquarius - start (January 20)' => ['2000-01-20', ZodiacSign::Aquarius],
            'Aquarius - middle (February 1)' => ['2000-02-01', ZodiacSign::Aquarius],
            'Aquarius - end (February 18)' => ['2000-02-18', ZodiacSign::Aquarius],

            'Pisces - start (February 19)' => ['2000-02-19', ZodiacSign::Pisces],
            'Pisces - middle (March 1)' => ['2000-03-01', ZodiacSign::Pisces],
            'Pisces - end (March 20)' => ['2000-03-20', ZodiacSign::Pisces],
        ];
    }

    public static function zodiacSignTimestampProvider(): array
    {
        return [
            'Aries (March 25, 2000)' => [954028800, ZodiacSign::Aries],
            'Taurus (May 1, 2000)' => [957139200, ZodiacSign::Taurus],
            'Gemini (June 1, 2000)' => [959817600, ZodiacSign::Gemini],
            'Cancer (July 1, 2000)' => [962409600, ZodiacSign::Cancer],
            'Leo (August 1, 2000)' => [965088000, ZodiacSign::Leo],
            'Virgo (September 1, 2000)' => [967766400, ZodiacSign::Virgo],
            'Libra (October 1, 2000)' => [970358400, ZodiacSign::Libra],
            'Scorpio (November 1, 2000)' => [973036800, ZodiacSign::Scorpio],
            'Sagittarius (December 1, 2000)' => [975628800, ZodiacSign::Sagittarius],
            'Capricorn (January 1, 2000)' => [946684800, ZodiacSign::Capricorn],
            'Aquarius (February 1, 2000)' => [949363200, ZodiacSign::Aquarius],
            'Pisces (March 1, 2000)' => [951868800, ZodiacSign::Pisces],
        ];
    }
}

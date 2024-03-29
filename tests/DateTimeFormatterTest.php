<?php

namespace Knp\Bundle\TimeBundle\Tests;

use Knp\Bundle\TimeBundle\DateTimeFormatter;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\Translation\TranslatorInterface;

final class DateTimeFormatterTest extends TestCase
{
    private DateTimeFormatter $formatter;

    public function setUp(): void
    {
        $translator = $this->getMockBuilder(TranslatorInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $translator->expects($this->any())
            ->method('trans')
            ->will($this->returnArgument(0));

        $this->formatter = new DateTimeFormatter($translator);
    }

    /**
     * @dataProvider getFormatDiffTests
     */
    public function testFormatDiff(string $fromString, ?string $toString, string $expected): void
    {
        $from = new \DateTimeImmutable(date('Y-m-d H:i:s', strtotime($fromString)));
        $to = null !== $toString ? new \DateTime(date('Y-m-d H:i:s', strtotime($toString))) : null;

        $this->assertSame($expected, $this->formatter->formatDiff($from, $to));
    }

    public function getFormatDiffTests(): \Generator
    {
        yield ['- 5 years', 'now', 'diff.ago.year'];
        yield ['- 10 months', 'now', 'diff.ago.month'];
        yield ['- 15 days', 'now', 'diff.ago.day'];
        yield ['- 20 hours', 'now', 'diff.ago.hour'];
        yield ['- 25 minutes', 'now', 'diff.ago.minute'];
        yield ['- 30 seconds', 'now', 'diff.ago.second'];
        yield ['now', 'now', 'diff.empty'];
        yield ['+ 30 seconds', 'now', 'diff.in.second'];
        yield ['+ 25 minutes', 'now', 'diff.in.minute'];
        yield ['+ 20 hours', 'now', 'diff.in.hour'];
        yield ['+ 15 days', 'now', 'diff.in.day'];
        yield ['+ 10 months', 'now', 'diff.in.month'];
        yield ['+ 5 years', 'now', 'diff.in.year'];
        yield ['+ 5 years', null, 'diff.in.year'];
        yield ['now', null, 'diff.empty'];
    }

    /**
     * @dataProvider formatTimeProvider
     *
     * @param int    $secs
     * @param string $expectedFormat
     */
    public function testFormatDuration($secs, $expectedFormat): void
    {
        $this->assertEquals($expectedFormat, $this->formatter->formatDuration($secs));
    }

    public static function formatTimeProvider(): iterable
    {
        return [
            [0,      'duration.none'],
            [1,      'duration.second'],
            [2,      'duration.second'],
            [59,     'duration.second'],
            [60,     'duration.minute'],
            [61,     'duration.minute'],
            [119,    'duration.minute'],
            [120,    'duration.minute'],
            [121,    'duration.minute'],
            [3599,   'duration.minute'],
            [3600,   'duration.hour'],
            [7199,   'duration.hour'],
            [7200,   'duration.hour'],
            [7201,   'duration.hour'],
            [86399,  'duration.hour'],
            [86400,  'duration.day'],
            [86401,  'duration.day'],
            [172799, 'duration.day'],
            [172800, 'duration.day'],
            [172801, 'duration.day'],
        ];
    }
}

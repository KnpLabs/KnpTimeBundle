<?php

namespace Knp\Bundle\TimeBundle\Tests;

use Knp\Bundle\TimeBundle\DateTimeFormatter;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\Translation\TranslatorInterface;

class DateTimeFormatterTest extends TestCase
{
    protected $formatter;

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
        $from = new \DatetimeImmutable(date('Y-m-d H:i:s', strtotime($fromString)));
        $to = $toString !== null ? new \Datetime(date('Y-m-d H:i:s', strtotime($toString))) : null;

        $this->assertSame($expected, $this->formatter->formatDiff($from, $to));
    }

    public function getFormatDiffTests(): \Generator
    {
        yield array('- 5 years', 'now', 'diff.ago.year');
        yield array('- 10 months', 'now', 'diff.ago.month');
        yield array('- 15 days', 'now', 'diff.ago.day');
        yield array('- 20 hours', 'now', 'diff.ago.hour');
        yield array('- 25 minutes', 'now', 'diff.ago.minute');
        yield array('- 30 seconds', 'now', 'diff.ago.second');
        yield array('now', 'now', 'diff.empty');
        yield array('+ 30 seconds', 'now', 'diff.in.second');
        yield array('+ 25 minutes', 'now', 'diff.in.minute');
        yield array('+ 20 hours', 'now', 'diff.in.hour');
        yield array('+ 15 days', 'now', 'diff.in.day');
        yield array('+ 10 months', 'now', 'diff.in.month');
        yield array('+ 5 years', 'now', 'diff.in.year');
        yield array('+ 5 years', null, 'diff.in.year');
        yield array('now', null, 'diff.empty');
    }

    public function testGetDiffMessage(): void
    {
        foreach (array('year', 'month', 'day', 'hour', 'minute', 'second') as $unit) {
            $this->assertEquals(sprintf('diff.in.%s', $unit), $this->formatter->getDiffMessage(1, false, $unit));
            $this->assertEquals(sprintf('diff.ago.%s', $unit), $this->formatter->getDiffMessage(1, true, $unit));
        }
    }

    public function testGetDiffMessageThrowsAnExceptionIfTheDiffIsEmpty(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->formatter->getDiffMessage(0, true, 'day');
    }

    public function testGetDiffMessageThrowsAnExceptionIfTheDiffUnitIsNotSupported(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->formatter->getDiffMessage(1, true, 'patate');
    }
}

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

    public function testFormatDiff()
    {
        $tests = array(
            array('- 5 years', 'now', 'diff.ago.year'),
            array('- 10 months', 'now', 'diff.ago.month'),
            array('- 15 days', 'now', 'diff.ago.day'),
            array('- 20 hours', 'now', 'diff.ago.hour'),
            array('- 25 minutes', 'now', 'diff.ago.minute'),
            array('- 30 seconds', 'now', 'diff.ago.second'),
            array('now', 'now', 'diff.empty'),
            array('+ 30 seconds', 'now', 'diff.in.second'),
            array('+ 25 minutes', 'now', 'diff.in.minute'),
            array('+ 20 hours', 'now', 'diff.in.hour'),
            array('+ 15 days', 'now', 'diff.in.day'),
            array('+ 10 months', 'now', 'diff.in.month'),
            array('+ 5 years', 'now', 'diff.in.year')
        );

        foreach ($tests as $test) {
            $from = new \DatetimeImmutable(date('Y-m-d H:i:s', strtotime($test[0])));
            $to = new \Datetime(date('Y-m-d H:i:s', strtotime($test[1])));

            $this->assertEquals($test[2], $this->formatter->formatDiff($from, $to));
        }
    }

    public function testGetDiffMessage()
    {
        foreach (array('year', 'month', 'day', 'hour', 'minute', 'second') as $unit) {
            $this->assertEquals(sprintf('diff.in.%s', $unit), $this->formatter->getDiffMessage(1, false, $unit));
            $this->assertEquals(sprintf('diff.ago.%s', $unit), $this->formatter->getDiffMessage(1, true, $unit));
        }
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testGetDiffMessageThrowsAnExceptionIfTheDiffIsEmpty()
    {
        $this->formatter->getDiffMessage(0, true, 'day');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testGetDiffMessageThrowsAnExceptionIfTheDiffUnitIsNotSupported()
    {
        $this->formatter->getDiffMessage(1, true, 'patate');
    }
}

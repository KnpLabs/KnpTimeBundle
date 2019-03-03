<?php

namespace Knp\Bundle\TimeBundle;

use DateInterval;
use DateTime;

class DateTimeFormatterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var DateTimeFormatter
     */
    protected $formatter;

    public function setUp()
    {
        $translator = $this->getMockBuilder('Symfony\Component\Translation\Translator')
            ->disableOriginalConstructor()
            ->getMock();
        $translator->expects($this->any())
            ->method('trans')
            ->will($this->returnArgument(0));
        $translator->expects($this->any())
            ->method('transChoice')
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

    public function testGetDiffMessageThrowsAnExceptionIfTheDiffIsEmpty()
    {
        $this->setExpectedException('InvalidArgumentException');

        $this->formatter->getDiffMessage(0, true, 'day');
    }

    public function testGetDiffMessageThrowsAnExceptionIfTheDiffUnitIsNotSupported()
    {
        $this->setExpectedException('InvalidArgumentException');

        $this->formatter->getDiffMessage(1, true, 'patate');
    }
    
    public function testFormatDiffMaxDiffSet()
    {
        $this->formatter->setMaxDiff(1);
        $this->formatter->setMaxDiffUnit('day');
        $format = 'd/m/Y';
        $this->formatter->setDateFormat($format);
        
        $now = new DateTime();
        
        $from = (clone $now)->sub(new DateInterval('P2D'));
        $to = clone $now;
        $result = $this->formatter->formatDiff($from, $to);
        $this->assertEquals($from->format($format), $result);
    
        $from = (clone $now)->sub(new DateInterval('PT10H'));
        $to = clone $now;
        
        $result = $this->formatter->formatDiff($from, $to);
        $this->assertEquals('diff.ago.hour', $result);
    
        //Other tests with 1 month
        $this->formatter->setMaxDiff(1);
        $this->formatter->setMaxDiffUnit('month');
    
        $from = (clone $now)->sub(new DateInterval('P2D'));
        $to = clone $now;
        $result = $this->formatter->formatDiff($from, $to);
        $this->assertEquals('diff.ago.day', $result);
        
        $from = (clone $now)->sub(new DateInterval('P1M'));
        $to = clone $now;
        $result = $this->formatter->formatDiff($from, $to);
        $this->assertEquals('diff.ago.month', $result);
    
        $from = (clone $now)->sub(new DateInterval('P35D'));
        $to = clone $now;
        $result = $this->formatter->formatDiff($from, $to);
        $this->assertEquals($from->format($format), $result);
    }
}

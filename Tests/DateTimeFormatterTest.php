<?php

namespace Knp\Bundle\TimeBundle;

class DateTimeFormatterTest extends \PHPUnit_Framework_TestCase
{
    protected $formatter;

    public function setUp()
    {
        $translator = $this->getMock('Symfony\Component\Translation\Translator', array(), array(), '', false);
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
            array('- 5 years', 'now', 'diff.ago.year',array('y')),
            array('- 10 months', 'now', 'diff.ago.month'),
            array('- 10 months', 'now', 'diff.ago.month',array('y','m')),
            array('- 15 days', 'now', 'diff.ago.day'),
            array('- 15 days', 'now', 'diff.ago.day',array('y','m','d')),
            array('- 20 hours', 'now', 'diff.ago.hour'),
            array('- 20 hours', 'now', 'diff.ago.hour',array('y','m','d','h')),
            array('- 25 minutes', 'now', 'diff.ago.minute'),
            array('- 25 minutes', 'now', 'diff.ago.minute',array('y','m','d','h','i')),
            array('- 30 seconds', 'now', 'diff.ago.second'),
            array('- 30 seconds', 'now', 'diff.ago.second',array('y','m','d','h','i','s')),
            array('now', 'now', 'diff.empty'),
            array('+ 30 seconds', 'now', 'diff.in.second'),
            array('+ 30 seconds', 'now', 'diff.in.second',array('s')),
            array('+ 25 minutes', 'now', 'diff.in.minute'),
            array('+ 25 minutes', 'now', 'diff.in.minute',array('i','s')),
            array('+ 20 hours', 'now', 'diff.in.hour'),
            array('+ 20 hours', 'now', 'diff.in.hour',array('h','i','s')),
            array('+ 15 days', 'now', 'diff.in.day'),
            array('+ 15 days', 'now', 'diff.in.day',array('d','h','i','s')),
            array('+ 10 months', 'now', 'diff.in.month'),
            array('+ 10 months', 'now', 'diff.in.month',array('m','d','h','i','s')),
            array('+ 5 years', 'now', 'diff.in.year'),
            array('+ 5 years', 'now', 'diff.in.year',array('y','m','d','h','i','s')),
            array('- 5 years 1 month 2 days', 'now', 'diff.ago.year diff.month diff.day', array('y','m','d'))
        );

        foreach ($tests as $test) {
            $from = new \Datetime(date('Y-m-d H:i:s', strtotime($test[0])));
            $to = new \Datetime(date('Y-m-d H:i:s', strtotime($test[1])));

            $this->assertEquals($test[2], $this->formatter->formatDiff($from, $to, ( array_key_exists(3, $test) === true ? $test[3] : array() ) ));
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
}

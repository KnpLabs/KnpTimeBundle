<?php

namespace Bundle\TimeBundle\Templating\Helper;

class TimeHelperTest extends \PHPUnit_Framework_TestCase
{
    protected $helper;

    public function setUp()
    {
        $this->helper = new TimeHelper();
    }

    public function testAgo()
    {
        $this->assertEquals('2 months ago', $this->helper->ago('2 months ago'));
        $this->assertEquals('1 month ago', $this->helper->ago('2 months ago', '1 month ago'));
    }

    public function testPluralize()
    {
        $this->assertEquals('months', $this->helper->pluralize(2, 'month'));
    }

    public function testPluralizeDoesNotDoAnythingWhenTheCountEqualsOne()
    {
        $this->assertEquals('month', $this->helper->pluralize(1, 'month'));
    }

    public function testGetDatetimeObject()
    {
        $this->assertEquals(date_create('2 months ago'), $this->helper->getDatetimeObject('2 months ago'));
        $this->assertEquals(date_create('now'), $this->helper->getDatetimeObject(time()));
        $this->assertEquals(date_create('now'), $this->helper->getDatetimeObject());
        $this->assertEquals(date_create('now'), $this->helper->getDatetimeObject(null));
    }
}

<?php

namespace Knp\Bundle\TimeBundle\Templating\Helper;

class TimeHelperTest extends \PHPUnit_Framework_TestCase
{
    protected $helper;

    public function setUp()
    {
        $this->helper = new TimeHelper($this->getMock('Knp\Bundle\TimeBundle\DateTimeFormatter', array(), array(), '', false));
    }

    public function testGetDatetimeObject()
    {
        $this->assertEquals(date_create('2 months ago'), $this->helper->getDatetimeObject('2 months ago'));
        $this->assertEquals(date_create('now'), $this->helper->getDatetimeObject(time()));
        $this->assertEquals(date_create('now'), $this->helper->getDatetimeObject());
        $this->assertEquals(date_create('now'), $this->helper->getDatetimeObject(null));
    }
}

<?php

namespace Knp\Bundle\TimeBundle\Templating\Helper;

class TimeHelperTest extends \PHPUnit_Framework_TestCase
{
    protected $helper;

    public function setUp()
    {
        $dateTimeFormatter = $this->getMockBuilder('Knp\Bundle\TimeBundle\DateTimeFormatter')
            ->disableOriginalConstructor()
            ->getMock();
        $this->helper = new TimeHelper($dateTimeFormatter);
    }

    public function testGetDatetimeObject()
    {
        $this->assertEquals(date_create('2 months ago'), $this->helper->getDatetimeObject('2 months ago'));
        $this->assertEquals(date_create('now'), $this->helper->getDatetimeObject(time()));
        $this->assertEquals(date_create('now'), $this->helper->getDatetimeObject());
        $this->assertEquals(date_create('now'), $this->helper->getDatetimeObject(null));
    }
}

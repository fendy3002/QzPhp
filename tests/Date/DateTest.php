<?php
namespace Test\DateTest;

class DateTest extends \Tests\TestCase
{
    public function testDateRangeToArray()
    {
        $from = \DateTime::createFromFormat("Y-m-d", "2018-01-01");
        $to = \DateTime::createFromFormat("Y-m-d", "2018-01-03");

        $result = \QzPhp\Q::Z()->date()->dateRangeToArray($from, $to);
        $this->assertEquals(3, count($result));
    }
    public function testDateDurationToArray()
    {
        $from = \DateTime::createFromFormat("Y-m-d", "2018-01-01");

        $result = \QzPhp\Q::Z()->date()->dateDurationToArray($from, 3);
        $this->assertEquals(3, count($result));
    }
}

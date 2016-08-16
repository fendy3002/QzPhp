<?php

namespace Tests\String;

class BetweenTest extends \Tests\TestCase
{
    public function testNormal()
    {
        $time = \QzPhp\Q::Z()->time();
        $data = [
            ["from" => "23.00", "to" => "08.00", 'now' => '07.00', 'expected' => true],
            ["from" => "23.00", "to" => "08.00", 'now' => '23.30', 'expected' => true],
            ["from" => "23.00", "to" => "08.00", 'now' => '11.00', 'expected' => false],
            ["from" => "23.00", "to" => "08.00", 'now' => '22.00', 'expected' => false],

            ["from" => "08.00", "to" => "23.00", 'now' => '06.00', 'expected' => false],
            ["from" => "08.00", "to" => "23.00", 'now' => '09.00', 'expected' => true],
            ["from" => "08.00", "to" => "23.00", 'now' => '22.00', 'expected' => true],
            ["from" => "08.00", "to" => "23.00", 'now' => '23.30', 'expected' => false]
        ];

        foreach($data as $each){
            $actual = $time->between($each['now'], $each['from'], $each['to']);
            $this->assertEquals($each['expected'], $actual);
        }
    }
}

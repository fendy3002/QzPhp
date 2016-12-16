<?php
namespace Tests\AutoMapper;

class ConverterTest extends \Tests\TestCase
{
    public function test()
    {
        $phones = [
            (object)[
                "phone"=> "0001111000"
            ],
            (object)[
                "phone"=> "0001111011"
            ]
        ];
        $expected = [
            "0001111000",
            "0001111011"
        ];
        $converter = new \QzPhp\AutoMapper\ValueConverter('array', 'phone');
        $actual = $converter->convert($phones);
        $this->assertEquals($expected, $actual);
    }
}

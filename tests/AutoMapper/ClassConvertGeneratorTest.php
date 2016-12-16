<?php
namespace Tests\AutoMapper;

class ClassConvertGeneratorTest extends \Tests\TestCase
{
    public function test()
    {
        $addresses = [
            (object)[
                "street_name"=> "Death star",
                "city"=> "Universe",
                "country"=> "Galaxy"
            ],
            (object)[
                "street_name"=> "Death star",
                "city"=> "Universe",
                "country"=> "Galaxy"
            ]
        ];

        $generator = new \QzPhp\AutoMapper\ClassConvertGenerator(
            'Models\Address',
            [
                "streetName"=> "street_name",
                "city"=>"",
                "country"=>""
            ]);
        $generator->generate();

        $expected = [];

        $converter = new \QzPhp\AutoMapper\Generated\Models_Address();
        $actual = $converter->convert($addresses, []);

        $this->assertEquals(2, count($actual));
    }
}

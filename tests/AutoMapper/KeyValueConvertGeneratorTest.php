<?php
namespace Tests\AutoMapper;

class KeyValueConvertGeneratorTest extends \Tests\TestCase
{
    public function test()
    {
        $educations = [
            (object)[
                "year"=> "2000",
                "organization"=> "Before Jedi Academy"
            ],
            (object)[
                "year"=> "2010",
                "organization"=> "Jedi Academy"
            ]
        ];

        $generator = new \QzPhp\AutoMapper\KeyValueConvertGenerator(
            'Models_Person_educations',
            'array',
            [
                'y' => 'year',
                'organization' => ''
            ]);
        eval($generator->generate());

        $expected = [
            (object)[
                "y"=> "2000",
                "organization"=> "Before Jedi Academy"
            ],
            (object)[
                "y"=> "2010",
                "organization"=> "Jedi Academy"
            ]
        ];

        $converter = new \QzPhp\AutoMapper\Generated\Models_Person_educations();
        $actual = $converter->convert($educations);
        $this->assertEquals($expected, $actual);
    }
}

<?php
namespace Test\AutoMapper;

class ConverterTest extends \Tests\TestCase
{
    public function test()
    {
        $schemaRaw = readfile(__DIR__ . '../../../storage/test/MapSchema/Schema.json');
        $schema = json_decode($schemaRaw);

        $person = [
            (object)[
                "name"=> "Luke",
                "birth"=> "2010/01/01",
                "identitication_no"=> "003",
                "mother_id"=> "001"
            ],
            (object)[
                "name"=> "Padme",
                "birth"=> "2010/01/01",
                "identitication_no"=> "001"
            ]
        ];
        $addresses = [
            (object)[
                "streetName"=> "Death star",
                "city"=> "Universe",
                "country"=> "Galaxy"
            ]
        ];
        $phones = [
            (object)[
                "phone"=> "0001111000"
            ]
        ];
        $educations = [
            (object)[
                "year"=> "2010",
                "organization"=> "Jedi Academy"
            ]
        ];
        $additionals = [
            "moms" => $moms,
            "phones" => $phones,
            "addresses" => $addresses,
            "educations" => $educations
        ];

        $converter = new \QzPhp\AutoMapper\Converter($schema, 'Models\\Person');
        $converter->convert($person, $additionals);
    }
}

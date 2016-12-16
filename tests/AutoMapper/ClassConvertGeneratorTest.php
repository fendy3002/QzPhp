<?php
namespace Tests\AutoMapper;

class ClassConvertGeneratorTest extends \Tests\TestCase
{
    /*public function testFields()
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
    }*/

    public function testValue()
    {
        $person = [
            (object)[
                "name"=> "Luke",
                "birth"=> "2010/01/01",
                "identitication_no"=> "003",
                "mother_id"=> "001"
            ]
        ];

        $phones = [
            (object)[
                "phone"=> "0001111000"
            ],
            (object)[
                "phone"=> "0001111011"
            ]
        ];


        $generator = new \QzPhp\AutoMapper\ClassConvertGenerator(
            'Models\Person',
            (object)[
                "name"=> "",
                "birth"=> "",
                "id"=> "identitication_no",
                "phones" => (object)[
                    "type"=> "array",
                    "value"=> "phone",
                    "key"=> (object)[
                        "id"=> "person_id"
                    ]
                ]
            ]);
        $generator->generate();

        $additionals = [
            "phones" => $phones
        ];
        $expected = [];

        $converter = new \QzPhp\AutoMapper\Generated\Models_Person();
        $actual = $converter->convert($person, $additionals);

        print_r($actual);
        //$this->assertEquals(2, count($actual));
    }
}

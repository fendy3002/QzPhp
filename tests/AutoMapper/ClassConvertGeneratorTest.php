<?php
namespace Tests\AutoMapper;

class ClassConvertGeneratorTest extends \Tests\TestCase
{
    public function testFields()
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

        $generator = new \QzPhp\AutoMapper\ClassConvertGenerator([
            "Address1" => (object)[
                "className" => 'Models\Address',
                "fields" => (object)[
                    "streetName"=> "street_name",
                    "city"=>"",
                    "country"=>""
                ]
            ]
        ]);
        $result = $generator->generate();
        foreach($result as $key=>$value){
            eval($value);
        }

        $expected = [];

        $converter = new \QzPhp\AutoMapper\Generated\Address1();
        $actual = $converter->convert($addresses, []);

        $this->assertEquals(2, count($actual));
    }

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


        $generator = new \QzPhp\AutoMapper\ClassConvertGenerator((object)[
            "Person1" => (object)[
                "className" => 'Models\Person',
                "fields" => (object)[
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
                ]
            ]
        ]);
        $result = $generator->generate();
        foreach($result as $key=>$value){
            eval($value);
        }

        $additionals = [
            "phones" => $phones
        ];
        $expected = [];

        $converter = new \QzPhp\AutoMapper\Generated\Person1();
        $actual = $converter->convert($person, $additionals);

        $this->assertEquals(1, count($actual));
    }
}

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
            "dateFormat" => "Y-m-d",
            "dateTimeFormat" => "Y-m-d"
        ]);
        $result = $generator->generate([
            "Version" => 1.0,
            "QzPhp\\AutoMapper\\Generated\\Address1" => (object)[
                "className" => 'Models\Address',
                "folder" => "generated",
                "fields" => (object)[
                    "streetName"=> "street_name",
                    "city"=>"",
                    "country"=>""
                ]
            ]
        ]);
        foreach($result as $key=>$value){
            file_put_contents(__DIR__ . '/generated1.txt', $value->definition);
            eval($value->definition);
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
                "id" => "01",
                "name"=> "Luke",
                "birth"=> "2017/12/17 01:12:23",
                "identitication_no"=> "003",
                "mother_id"=> "001",
                "rating" => "1231"
            ]
        ];

        $phones = [
            (object)[
                "person_id" => "01",
                "phone"=> "0001111000"
            ],
            (object)[
                "person_id" => "01",
                "phone"=> "0001111011"
            ]
        ];

        $generator = new \QzPhp\AutoMapper\ClassConvertGenerator([
            "dateFormat" => "Y-m-d",
            "dateTimeFormat" => "Y-m-d H:i:s"
        ], [
            "Models\Person" => (object)[
                "fields" => (object)[
                    "birth" => "date",
                    "rating" => "int"
                ]
            ]
        ]);
        $result = $generator->generate([
            "Version" => 1.0,
            "QzPhp\\AutoMapper\\Generated\\Person1" => (object)[
                "className" => 'Models\Person',
                "folder" => "generated",
                "fields" => (object)[
                    "name"=> "",
                    "birth"=> "",
                    "id"=> "identitication_no",
                    "rating"=> "",
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

        foreach($result as $key=>$value){
            file_put_contents(__DIR__ . '/generated2.txt', $value->definition);
            eval($value->definition);
        }

        $additionals = [
            "phones" => $phones
        ];
        $expected = [];

        $converter = new \QzPhp\AutoMapper\Generated\Person1();
        $actual = $converter->convert($person, $additionals);

        $this->assertEquals(1, count($actual));
        $this->assertEquals("2017-12-17", $actual[0]->birth);
        $this->assertEquals(1231, $actual[0]->rating);
        $this->assertEquals("integer", gettype($actual[0]->rating));
    }
}

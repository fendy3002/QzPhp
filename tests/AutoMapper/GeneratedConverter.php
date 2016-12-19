<?php
namespace Tests\AutoMapper;

class GeneratedConverter extends \Tests\TestCase
{
    public function testConvert()
    {
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
                "identitication_no"=> "001",
                "mother_id"=> NULL
            ]
        ];
        $addresses = [
            (object)[
                "person_id" => "003",
                "streetName"=> "Death star",
                "city"=> "Universe",
                "country"=> "Galaxy"
            ]
        ];
        $phones = [
            (object)[
                "person_id" => "003",
                "phone"=> "0001111000",
                "isDefault" => false
            ],
            (object)[
                "person_id" => "003",
                "phone"=> "0001111011",
                "isDefault" => true
            ]
        ];
        $defaultPhone = [
            (object)[
                "person_id" => "003",
                "phone"=> "0001111011",
                "isDefault" => true
            ]
        ];
        $educations = [
            (object)[
                "person_id" => "003",
                "year"=> "2010",
                "organization"=> "Jedi Academy"
            ]
        ];
        $experiences = [
            (object)[
                "year"=> "2014",
                "organization"=> "Pod racer",
                "isDefault" => false
            ],
            (object)[
                "year"=> "2015",
                "organization"=> "Rebellion",
                "isDefault" => true
            ]
        ];
        $defaultExperiences = [
            (object)[
                "year"=> "2015",
                "organization"=> "Rebellion",
                "isDefault" => true
            ]
        ];
        $additionals = [
            "moms" => $person,
            "phones" => $phones,
            "addresses" => $addresses,
            "educations" => $educations,
            "experiences" => $experiences,
            "defaultPhone" => $defaultPhone,
            "defaultExperiences" => $defaultExperiences
        ];

        $converter = new \Generated\Converter\Person1();
        $result = $converter->convert($person, $additionals);
        print_r($result);
    }
}

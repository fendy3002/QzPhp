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

        $vehicleLink = [
            (object)[
                "person_id" => "003",
                "vehicle_id" => "suzukir3"
            ],
            (object)[
                "person_id" => "003",
                "vehicle_id" => "hondamobilio"
            ],
            (object)[
                "person_id" => "001",
                "vehicle_id" => "mitsubishiexpander"
            ]
        ];

        $vehicles = [
            (object)[
                "id" => "hondamobilio",
                "manufacturer" => "Honda",
                "model" => "Mobilio"
            ],
            (object)[
                "id" => "mitsubishiexpander",
                "manufacturer" => "Mitsubishi",
                "model" => "Expander"
            ],
            (object)[
                "id" => "suzukir3",
                "manufacturer" => "Suzuki",
                "model" => "Ertiga"
            ]
        ];
        $additionals = [
            "moms" => $person,
            "phones" => $phones,
            "addresses" => $addresses,
            "educations" => $educations,
            "experiences" => $experiences,
            "defaultPhone" => $defaultPhone,
            "defaultExperiences" => $defaultExperiences,
            "vehicles" => $vehicles,
            "vehicles_link" => $vehicleLink
        ];

        $converter = new \Generated\Converter\Person1();
        $result = $converter->convert($person, $additionals);

        $this->assertEquals(2, count($result));
        $this->assertEquals("Luke", $result[0]->name);
        $this->assertEquals(2, count($result[0]->vehicles));
        $this->assertEquals(2, count($result[0]->experiences));
        $this->assertEquals(2, count($result[0]->phones));

        $this->assertEquals(1, count($result[1]->vehicles));
        $this->assertEquals(2, count($result[1]->experiences));
    }
}

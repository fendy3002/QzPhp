<?php
namespace Test\Linq;

class ToKeyArrayTest extends \Tests\TestCase
{
    public function test1()
    {
        $data = [
            (object)['name' => 'name1', 'address' => 'address1'],
            (object)['name' => 'name1', 'address' => 'address12'],
            (object)['name' => 'name2', 'address' => 'address2'],
            (object)['name' => 'name3', 'address' => 'address3']
        ];

        $expected = [
            'name1' => [
                'address1',
                'address12'
            ],
            'name2' => [
                'address2'
            ],
            'name3' => [
                'address3'
            ]
        ];

        $actual = \QzPhp\Q::Z()->enum($data)
            ->toKeyArray(function($k){ return $k->name; }, function($k){ return $k->address; })
            ->result();
        $this->assertEquals($expected, $actual);
    }
}

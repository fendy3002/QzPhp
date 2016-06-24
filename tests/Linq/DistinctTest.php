<?php
namespace Test\Linq;

class DistinctTest extends \Tests\TestCase
{
    public function test()
    {
        $data = [
            (object)['name' => 'name1', 'address' => 'address1', 'value' => 15],
            (object)['name' => 'name1', 'address' => 'address1', 'value' => 15],
            (object)['name' => 'name2', 'address' => 'address2', 'value' => 10],
            (object)['name' => 'name2', 'address' => 'address2', 'value' => 10],
            (object)['name' => 'name3', 'address' => 'address3', 'value' => 20],
            (object)['name' => 'name3', 'address' => 'address3', 'value' => 20],
            (object)['name' => 'name3', 'address' => 'address3', 'value' => 20]
        ];

        $expected = [
            (object)['name' => 'name1', 'address' => 'address1', 'value' => 15],
            (object)['name' => 'name2', 'address' => 'address2', 'value' => 10],
            (object)['name' => 'name3', 'address' => 'address3', 'value' => 20]
        ];

        $actual = \QzPhp\Q::Z()->enum($data)
            ->distinct(function($k, $l){
                return $k->name == $l->name;
            })->result();
        $this->assertEquals($expected, $actual);
    }
}

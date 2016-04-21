<?php
namespace Test\Linq;

class OrderByTest extends \Tests\TestCase
{
    public function test1()
    {
        $data = [
            (object)['name' => 'name1', 'address' => 'address1', 'value' => 15],
            (object)['name' => 'name2', 'address' => 'address2', 'value' => 10],
            (object)['name' => 'name3', 'address' => 'address3', 'value' => 20]
        ];

        $expected = [
            (object)['name' => 'name2', 'address' => 'address2', 'value' => 10],
            (object)['name' => 'name1', 'address' => 'address1', 'value' => 15],
            (object)['name' => 'name3', 'address' => 'address3', 'value' => 20]
        ];

        $actual = \QzPhp\Q::Z()->enum($data)->orderBy(function($k, $l){ return $k->value < $l->value; })->result();
        $this->assertEquals($expected, $actual);
    }
    public function test2()
    {
        $data = [
            (object)['name' => 'name1', 'address' => 'address1', 'value' => 15],
            (object)['name' => 'name2', 'address' => 'address2', 'value' => 10],
            (object)['name' => 'name3', 'address' => 'address3', 'value' => 20]
        ];

        $expected = [
            (object)['name' => 'name3', 'address' => 'address3', 'value' => 20],
            (object)['name' => 'name2', 'address' => 'address2', 'value' => 10],
            (object)['name' => 'name1', 'address' => 'address1', 'value' => 15]
        ];

        $actual = \QzPhp\Q::Z()->enum($data)->orderBy(function($k, $l){ return $k->name > $l->name; })->result();
        $this->assertEquals($expected, $actual);
    }
}

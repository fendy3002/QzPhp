<?php
namespace Test\Linq;

class SelectTest extends \Tests\TestCase
{
    public function test1()
    {
        $data = [
            (object)['name' => 'name1', 'address' => 'address1'],
            (object)['name' => 'name2', 'address' => 'address2'],
            (object)['name' => 'name3', 'address' => 'address3']
        ];

        $expected = [
            'name1',
            'name2',
            'name3'
        ];

        $actual = \QzPhp\Q::Z()->enum($data)->select(function($k){ return $k->name; })->result();
        $this->assertEquals($expected, $actual);
    }
    public function test2()
    {
        $data = [
            (object)['name' => 'name1', 'address' => 'address1'],
            (object)['name' => 'name2', 'address' => 'address2'],
            (object)['name' => 'name3', 'address' => 'address3']
        ];

        $expected = [
            (object)['name' => 'name1'],
            (object)['name' => 'name2'],
            (object)['name' => 'name3']
        ];

        $actual = \QzPhp\Q::Z()->enum($data)->select(function($k){ return (object)['name' => $k->name]; })->result();
        $this->assertEquals($expected, $actual);
    }
}

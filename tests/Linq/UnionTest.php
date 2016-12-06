<?php
namespace Test\Linq;

class UnionTest extends \Tests\TestCase
{
    public function test1()
    {
        $data1 = [
            (object)['name' => 'name1', 'address' => 'address1'],
            (object)['name' => 'name2', 'address' => 'address2'],
            (object)['name' => 'name3', 'address' => 'address3']
        ];
        $data2 = [
            (object)['name' => 'name1', 'address' => 'address1'],
            (object)['name' => 'name2', 'address' => 'address2'],
            (object)['name' => 'name3', 'address' => 'address3']
        ];

        $expected = [
            (object)['name' => 'name1', 'address' => 'address1'],
            (object)['name' => 'name2', 'address' => 'address2'],
            (object)['name' => 'name3', 'address' => 'address3'],
            (object)['name' => 'name1', 'address' => 'address1'],
            (object)['name' => 'name2', 'address' => 'address2'],
            (object)['name' => 'name3', 'address' => 'address3']
        ];

        $actual = \QzPhp\Q::Z()->enum($data1)->union($data2)->result();
        $this->assertEquals($expected, $actual);
    }
}

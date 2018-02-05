<?php
namespace Test\Linq;

class GetMaxMinByTest extends \Tests\TestCase
{
    public function testGetByMax()
    {
        $data = [
            (object)['id' => '01', 'name' => 'name1', 'address' => 'address1', 'value' => 15],
            (object)['id' => '02', 'name' => 'name2', 'address' => 'address2', 'value' => 10],
            (object)['id' => '03', 'name' => 'name3', 'address' => 'address3', 'value' => 20],
            (object)['id' => '04', 'name' => 'name4', 'address' => 'address4', 'value' => 25]
        ];
    
        $expected = (object)['id' => '04', 'name' => 'name4', 'address' => 'address4', 'value' => 25];

        $actual = \QzPhp\Q::Z()->enum($data)
            ->getByMax(function($k){ return $k->value; });
        $this->assertEquals($expected, $actual);
    }
    public function testGetByMin()
    {
        $data = [
            (object)['id' => '01', 'name' => 'name1', 'address' => 'address1', 'value' => 15],
            (object)['id' => '02', 'name' => 'name2', 'address' => 'address2', 'value' => 10],
            (object)['id' => '03', 'name' => 'name3', 'address' => 'address3', 'value' => 20],
            (object)['id' => '04', 'name' => 'name4', 'address' => 'address4', 'value' => 25]
        ];
    
        $expected = (object)['id' => '02', 'name' => 'name2', 'address' => 'address2', 'value' => 10];

        $actual = \QzPhp\Q::Z()->enum($data)
            ->getByMin(function($k){ return $k->value; });
        $this->assertEquals($expected, $actual);
    }
}

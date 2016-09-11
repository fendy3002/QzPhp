<?php
namespace Test\Linq;

class GroupByTest extends \Tests\TestCase
{
    public function test1()
    {
        $data = [
            (object)['name' => 'name1', 'address' => 'address1', 'value' => 15],
            (object)['name' => 'name1', 'address' => 'address2', 'value' => 10],
            (object)['name' => 'name2', 'address' => 'address3', 'value' => 20],
            (object)['name' => 'name2', 'address' => 'address3', 'value' => 30],
            (object)['name' => 'name3', 'address' => 'address3', 'value' => 25]
        ];

        $expected = [
            (object)['key' => 'name1', 'value' => [
                    (object)['name' => 'name1', 'address' => 'address1', 'value' => 15],
                    (object)['name' => 'name1', 'address' => 'address2', 'value' => 10]
                ]],
            (object)['key' => 'name2', 'value' => [
                    (object)['name' => 'name2', 'address' => 'address3', 'value' => 20],
                    (object)['name' => 'name2', 'address' => 'address3', 'value' => 30]
                ]],
            (object)['key' => 'name3', 'value' => [
                    (object)['name' => 'name3', 'address' => 'address3', 'value' => 25]
                ]]
        ];

        $actual = \QzPhp\Q::Z()->enum($data)
            ->groupBy(function($k){
                return $k->name;
            })->result();

        $this->assertEquals($expected, $actual);
    }
}

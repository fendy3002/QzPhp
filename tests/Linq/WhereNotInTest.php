<?php
namespace Test\Linq;

class WhereNotInTest extends \Tests\TestCase
{
    public function test1()
    {
        $data = [
            (object)['id' => '01', 'name' => 'name1', 'address' => 'address1', 'value' => 15],
            (object)['id' => '02', 'name' => 'name2', 'address' => 'address2', 'value' => 10],
            (object)['id' => '03', 'name' => 'name3', 'address' => 'address3', 'value' => 20],
            (object)['id' => '04', 'name' => 'name4', 'address' => 'address4', 'value' => 25]
        ];
        $compared = [
            (object)['id' => '01', 'name' => 'name1', 'address' => 'address1', 'value' => 15],
            (object)['id' => '03', 'name' => 'name3', 'address' => 'address3', 'value' => 20]
        ];

        $expected = [
            (object)['id' => '02', 'name' => 'name2', 'address' => 'address2', 'value' => 10],
            (object)['id' => '04', 'name' => 'name4', 'address' => 'address4', 'value' => 25]
        ];

        $actual = \QzPhp\Q::Z()->enum($data)
            ->whereNotIn($compared, function($k, $l){ return $k->id == $l->id; })->result();
        $this->assertEquals($expected, $actual);
    }
}

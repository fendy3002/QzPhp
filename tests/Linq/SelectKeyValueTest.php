<?php
namespace Test\Linq;

class SelectKeyValueTest extends \Tests\TestCase
{
    public function test1()
    {
        $data = ['name' => 'name1', 'address' => 'address1'];

        $expected = [
            'name',
            'address'
        ];

        $actual = \QzPhp\Q::Z()->enum($data)->selectKeyValue(function($k, $l){ return $k; })->result();
        $this->assertEquals($expected, $actual);
    }
    public function test2()
    {
        $data = [
            '001.jpg' => [
                'type' => 'jpeg',
                'location' => '/var/www/html'
            ],
            '002.jpg' => [
                'type' => 'jpeg',
                'location' => '/var/www/html'
            ]];

        $expected = [
            '001.jpg' => '/var/www/html',
            '002.jpg' => '/var/www/html'
        ];

        $actual = \QzPhp\Q::Z()->enum($data)
            ->selectKeyValue(function($k, $l){ return [$k => $l['location']]; })
            ->flat()
            ->result();
        $this->assertEquals($expected, $actual);
    }
}

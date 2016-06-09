<?php
namespace Test\IO;

class BasenameTest extends \Tests\TestCase
{
    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function test()
    {
        $io = new \QzPhp\IO();
        $data = [
            ['path' => 'http://i.imgur.com/etjgJ2D.jpg'],
            ['path' => 'http://i.imgur.com/etjgJ2D.jpg?s=400&q=hd'],
            ['path' => 'http://i.imgur.com/test/etjgJ2D.jpg']
        ];
        $expected = 'etjgJ2D.jpg';
        foreach($data as $each){
            $result = $io->basename($each['path']);
            $this->assertEquals($expected, $result);
        }
    }
}

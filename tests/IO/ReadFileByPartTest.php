<?php
namespace Test\IO;

class ReadFileByPartTest extends \Tests\TestCase
{
    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function test()
    {
        $io = new \QzPhp\IO();
        $d = DIRECTORY_SEPARATOR;
        $path = __DIR__ . $d . '..' . $d . '..' . $d . 'storage' . $d . 'test' . $d . 'file.txt';
        $listener = (object)[
            "count" => 0
        ];
        $io->readFileByPart($path, function($content) use($listener){
            $listener->count++;
        }, 128);
        $this->assertEquals(6, $listener->count);
    }
}

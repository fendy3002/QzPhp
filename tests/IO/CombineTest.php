<?php
namespace Test\IO;

class CombineTest extends \Tests\TestCase
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
        $data = [
            ['path' => ['hello', 'world'], 'expected' => 'hello' . $d . 'world'],
            ['path' => ['hello'. $d, 'world'], 'expected' => 'hello' . $d . 'world'],
            ['path' => ['hello' . $d, 'world' . $d], 'expected' => 'hello' . $d . 'world']
        ];
        foreach($data as $each){
            $result = call_user_func_array(array($io, "combine"), $each['path']);
            $this->assertEquals($each['expected'], $result);
        }
    }
}

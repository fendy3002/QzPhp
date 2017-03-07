<?php
namespace Tests\Arr;

class ArrTest extends \Tests\TestCase
{
    public function testGet()
    {
        $data = [
            "a" => 1
        ];
        $arr = \QzPhp\Q::Z()->arr($data);
        $this->assertEquals(1, $arr->get("a"));
        $this->assertEquals(null, $arr->get("b"));
    }

    public function testSet(){
        $data = [
            "a" => 1
        ];
        $arr = \QzPhp\Q::Z()->arr($data);
        $arr->set("a", 2);
        $arr->set("c", 3);
        $this->assertEquals(2, $arr->get("a"));
        $this->assertEquals(null, $arr->get("b"));
        $this->assertEquals(3, $arr->get("c"));
    }
}

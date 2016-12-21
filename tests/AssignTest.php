<?php
namespace Test;

class AssignTest extends \Tests\TestCase
{
    public function testGenerate()
    {
        $result = \QzPhp\Q::Z()->assign(new \Generated\Models\Person(), [
            "name" => "Luke Skywalker",
            "birth" => "01-01-2000",
            "id" => "001"
        ]);

        $this->assertEquals("001", $result->id);
    }
    public function testPropertyNotExists()
    {
        try{
            $result = \QzPhp\Q::Z()->assign(new \Generated\Models\Person(), [
                "name" => "Luke Skywalker",
                "birth" => "01-01-2000",
                "id2" => "001"
            ]);
        }
        catch(\Exception $ex){
            $this->assertEquals("Property id2 not exists in class: Generated\\Models\\Person", $ex->getMessage());
        }
    }
}

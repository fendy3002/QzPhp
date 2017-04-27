<?php
namespace Tests\BoolToYesNo;

class GetTest extends \Tests\TestCase
{
    public function testGet()
    {
        $result = \QzPhp\Q::Z()->boolToYesNo(["language" => "de"])->get(true);
        $expected = "ja";
        $this->assertEquals($expected, $result);

        $result2 = \QzPhp\Q::Z()->boolToYesNo(["language" => "id"])->get(false, ["case" => "upper"]);
        $expected2 = "TIDAK";
        $this->assertEquals($expected2, $result2);
    }
}

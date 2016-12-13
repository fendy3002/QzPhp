<?php

namespace Tests\Models;
use QzPhp\Q;

class ResultTest extends \Tests\TestCase
{
    public function testConstruct()
    {
        $expectedData = Q::Z()->uuid();
        $expectedMessage = "Hello World";
        $actualResult = Q::Z()->result([
            "data" => $expectedData,
            "message" => $expectedMessage
        ]);

        $this->assertEquals($expectedData, $actualResult->data());
        $this->assertEquals([$expectedMessage], $actualResult->messages());
    }
}

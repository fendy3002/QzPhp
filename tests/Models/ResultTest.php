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
    public function testConstructMessage()
    {
        $expectedMessage = "Hello Luke, I am your father";
        $actualResult = Q::Z()->result([
            "messages" => [
                'Hello Luke',
                'I am your father'
            ]
        ]);

        $this->assertEquals($expectedMessage, $actualResult->message());
        $this->assertEquals($expectedMessage, $actualResult->message(', '));
    }
}

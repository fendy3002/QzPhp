<?php
namespace Test\Q;

class MockTest extends \Tests\TestCase
{
    public function testNormal()
    {
        $mockClass = (object)["mock" => true];
        \QzPhp\Q::Z()->addMock("url", $mockClass);
        $getClass = \QzPhp\Q::Z()->url();
        $this->assertEquals($mockClass, $getClass);
        \QzPhp\Q::Z()->clearMock();
    }
    public function testAll()
    {
        $log = new \QzPhp\Logs\EmptyLog();
        $mockClass = (object)["mock" => true];
        
        $mocks = [
            "arr" => $mockClass,
            'string' => $mockClass,
            'io' => $mockClass,
            'geo' => $mockClass,
            'url' => $mockClass,
            'curl' => $mockClass,
            'time' => $mockClass,
            'enum' => $mockClass,
            'boolToYesNo' => $mockClass
        ];

        \QzPhp\Q::Z()->addMocks($mocks);
        $log->messageln('arr');
        $obj = \QzPhp\Q::Z()->arr([]);
        $this->assertEquals($mockClass, $obj);
        $log->messageln('string');
        $obj = \QzPhp\Q::Z()->string();
        $this->assertEquals($mockClass, $obj);
        $log->messageln('io');
        $obj = \QzPhp\Q::Z()->io();
        $this->assertEquals($mockClass, $obj);
        $log->messageln('geo');
        $obj = \QzPhp\Q::Z()->geo();
        $this->assertEquals($mockClass, $obj);
        $log->messageln('url');
        $obj = \QzPhp\Q::Z()->url();
        $this->assertEquals($mockClass, $obj);
        $log->messageln('curl');
        $obj = \QzPhp\Q::Z()->curl('');
        $this->assertEquals($mockClass, $obj);
        $log->messageln('time');
        $obj = \QzPhp\Q::Z()->time();
        $this->assertEquals($mockClass, $obj);
        $log->messageln('enum');
        $obj = \QzPhp\Q::Z()->enum([]);
        $this->assertEquals($mockClass, $obj);
        $log->messageln('boolToYesNo');
        $obj = \QzPhp\Q::Z()->boolToYesNo([]);
        $this->assertEquals($mockClass, $obj);
        
        $log->messageln('DONE');
        \QzPhp\Q::Z()->clearMock();
    }
}

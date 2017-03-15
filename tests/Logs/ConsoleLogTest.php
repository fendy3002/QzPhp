<?php
namespace Test\Logs;

class ConsoleLogTest extends \Tests\TestCase
{
    public function testMessage()
    {
        $text = 'Hello world, this is optimus prime';
        $consoleLog = new \QzPhp\Logs\ConsoleLog();
        $consoleLog->message($text);

        $this->expectOutputString($text);
    }
    public function testMessageLn()
    {
        $text = 'Hello world, this is optimus prime';
        $consoleLog = new \QzPhp\Logs\ConsoleLog();
        $consoleLog->messageLn($text);

        $this->expectOutputString($text . "\n");
    }
    public function testMessageLnWithFormat()
    {
        $text = '<!DOCTYPE> This is %n';
        $consoleLog = new \QzPhp\Logs\ConsoleLog();
        $consoleLog->messageLn($text);
        $this->expectOutputString($text . "\n");
    }
}

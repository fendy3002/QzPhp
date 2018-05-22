<?php

namespace Tests\Queue;

class DispatchTest extends \Tests\TestCase
{
    public function testDispatch()
    {
        $conf = include(__DIR__ . "/../../testconf.php");
        $conf = $conf['db'];
        $dispatcher = new \QzPhp\Queue\Dispatcher([
            "connection" => [
                "host" => $conf["host"],
                "user" => $conf["username"],
                "password" => $conf["password"],
                "database" => $conf["database"],
                "port" => $conf["port"]
            ],
            "timezone" => "Asia/Jakarta"
        ]);
        $dispatchResult = $dispatcher->dispatch(\QzPhp\Q::Z()->io()->combine(__DIR__, "__testscript.js"), [
            "param1" => "value1",
            "param2" => "value2",
        ], [
            "when" => "2018-01-01T00:00:00"
        ]);
        $this->assertEquals(true, !empty($dispatchResult->uuid));
    }
}

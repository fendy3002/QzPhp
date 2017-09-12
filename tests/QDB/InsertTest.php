<?php
namespace Test\QDB;

class InsertTest extends \Tests\TestCase
{
    public function testNormal()
    {
        $conf = include(__DIR__ . "/../../testconf.php");
        $dbGenerator = new \QzPhp\DBGenerator();
        $dbh = $dbGenerator->get($conf['host'], $conf['username'], $conf['password'], $conf['database'], $conf['port']);
        $log = new \QzPhp\Logs\ConsoleLog();
        $qdb = new \QzPhp\QDB($dbh, $log);

        $qdb->statement("truncate table employee;");
        $qdb->insert("employee", [
            [
                "name" => "Luke Skywalker"
            ]
        ]);
        $emp = $qdb->select("select * from employee;");
        $this->assertEquals(1, count($emp));

        $qdb->statement("truncate table employee;");
    }
    public function testWrongTable()
    {
        $context = (object)[
            "exception" => 0
        ];

        $conf = include(__DIR__ . "/../../testconf.php");
        $dbGenerator = new \QzPhp\DBGenerator();
        $dbh = $dbGenerator->get($conf['host'], $conf['username'], $conf['password'], $conf['database'], $conf['port']);
        $log = new \QzPhp\Logs\ConsoleLog();
        $qdb = new \QzPhp\QDB($dbh, $log);

        $qdb->statement("truncate table employee;");
        try{
            $qdb->insert("employee2", [
                [
                    "name" => "Luke Skywalker"
                ]
            ]);
        }
        catch(\PDOException $ex){
            $context->exception++;
        }

        $emp = $qdb->select("select * from employee;");
        $this->assertEquals(0, count($emp));
        $this->assertEquals(1, $context->exception);

        $qdb->statement("truncate table employee;");
    }
}

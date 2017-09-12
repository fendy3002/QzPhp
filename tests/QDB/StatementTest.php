<?php
namespace Test\QDB;

class StatementTest extends \Tests\TestCase
{
    public function testException()
    {
        $conf = include(__DIR__ . "/../../testconf.php");
        $this->expectException(\PDOException::class);

        $log = new \QzPhp\Logs\ConsoleLog();

        $dbGenerator = new \QzPhp\DBGenerator();
        $dbh = $dbGenerator->get($conf['host'], 
            $conf['username'], 
            $conf["password"], 
            $conf["database"]);
        $qdb = new \QzPhp\QDB($dbh, $log);
            $qdb->statement("Hello");
    }
}
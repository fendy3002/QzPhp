<?php
namespace Test\DBGenerator;

class DBGeneratorTest extends \Tests\TestCase
{
    public function testZ()
    {
        $conf = include(__DIR__ . "/../../testconf.php");

        $qdb = \QzPhp\Q::Z()->db([
            'host' => $conf['host'],
            'user' => $conf['username'],
            'password' => $conf['password'],
            'database' => $conf['database'],
        ]);
        $this->assertEquals(1, count($qdb->select("select 1 as 'col1'")));
    }
}

<?php
namespace Test\DBGenerator;

class DBGeneratorTest extends \Tests\TestCase
{
    public function testZ()
    {
        $qdb = \QzPhp\Q::Z()->db([
            'host' => '127.0.0.1',
            'user' => 'root',
            'password' => 'password',
            'database' => 'mysql',
        ]);
        $this->assertEquals(1, count($qdb->select("select 1 as 'col1'")));
    }
    public function testException()
    {
        $this->expectException(\PDOException::class);

        $dbGenerator = new \QzPhp\DBGenerator();
        $dbh = $dbGenerator->get("127.0.0.1", "root", "password", "mysql");
        $qdb = new \QzPhp\QDB($dbh);
        $qdb->statement("Hello");
    }
}

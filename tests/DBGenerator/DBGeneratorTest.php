<?php
namespace Test\DBGenerator;

class DBGeneratorTest extends \Tests\TestCase
{
    public function testException()
    {
        $this->expectException(\PDOException::class);

        $dbGenerator = new \QzPhp\DBGenerator();
        $dbh = $dbGenerator->get("127.0.0.1", "root", "password", "hotelsource_db");
        $qdb = new \QzPhp\QDB($dbh);
        $qdb->statement("Hello");
    }
}

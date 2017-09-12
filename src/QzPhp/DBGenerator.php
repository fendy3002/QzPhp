<?php
namespace QzPhp;

class DBGenerator{
    public function get($host, $user, $password, $database, $port = 3306){
        $dsn = 'mysql:dbname=' . $database . ';host=' . $host . ";port=" . $port;
        $dbh = new \PDO($dsn, $user, $password, [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
        ]);
        return $dbh;
    }
}

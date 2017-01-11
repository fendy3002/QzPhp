<?php
namespace QzPhp;

class DBGenerator{
    public function get($host, $user, $password, $database){
        $dsn = 'mysql:dbname=' . $database . ';host=' . $host;
        $dbh = new \PDO($dsn, $user, $password, [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
        ]);
        return $dbh;
    }
}

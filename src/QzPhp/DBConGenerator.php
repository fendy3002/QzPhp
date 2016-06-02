<?php
namespace QzPhp;

class DBConGenerator{
    public function generate($host, $database, $user, $password){
        $dsn = 'mysql:dbname=' . $database . ';host=' . $host;
        return $dsn;
    }
}

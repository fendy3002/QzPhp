<?php
namespace QzPhp;

class DBConGenerator{
    public function generate($host, $database){
        $dsn = 'mysql:dbname=' . $database . ';host=' . $host;
        return $dsn;
    }
}

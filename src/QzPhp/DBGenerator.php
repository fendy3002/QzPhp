<?php
namespace QzPhp;

class DBGenerator{
    public function get(){
        $host = env('DB_HOST', '');
        $database = env('DB_DATABASE', '');
        $user = env('DB_USERNAME', '');
        $password = env('DB_PASSWORD', '');

        $dsn = 'mysql:dbname=' . $database . ';host=' . $host;
        $dbh = new \PDO($dsn, $user, $password);
        return $dbh;
    }
}

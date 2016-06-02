<?php
namespace QzPhp;

class DBConGenerator{
    public function generate($host, $database, $user, $password){
        $dsn = 'mysql:dbname=' . $database . ';host=' . $host;
        if(!empty($user)){
            $dsn .= ';username=' . $user;
        }
        if(!empty($password)){
            $dsn .= ';password=' . $password;
        }
        return $dsn;
    }
}

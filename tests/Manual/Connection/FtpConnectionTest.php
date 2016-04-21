<?php

namespace Tests\Connection;

class FtpConnectionTest extends \TestCase
{
    public function testFtp()
    {
        $basepath = '';
        if(strtoupper(substr(PHP_OS, 0, 3)) === 'WIN'){
            $basepath = 'D:\temp\_file';
        }
        else{
            $basepath = '/temp/_file';
        }

        $connectionString = 'ftp://192.168.10.5;user=ftpuser;password=1234;';
        $builder = new \QzPhp\ConnectionBuilder();
        $conn = $builder->build($connectionString);
        $conn->send($basepath . DIRECTORY_SEPARATOR . '001.txt');
    }
}

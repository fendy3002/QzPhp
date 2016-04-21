<?php

namespace Tests\Connection;

class LocalFileConnectionTest extends \TestCase
{
    public function testLocalFile()
    {
        $basepath = '';
        if(strtoupper(substr(PHP_OS, 0, 3)) === 'WIN'){
            $basepath = 'D:\temp\_file';
        }
        else{
            $basepath = '/temp/_file';
        }

        $connectionString = 'file://local;path=' . $basepath;
        $builder = new \QzPhp\ConnectionBuilder();
        $conn = $builder->build($connectionString);
        $conn->send($basepath . DIRECTORY_SEPARATOR . '001.txt', $basepath . DIRECTORY_SEPARATOR . '002.txt');
    }
}

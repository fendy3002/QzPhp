<?php

namespace Tests\Connection;

class LocalFileConnectionTest extends \Tests\TestCase
{
    public function testLocalFile()
    {
        $basepath = '';
        if(strtoupper(substr(PHP_OS, 0, 3)) === 'WIN'){
            $basepath = 'D:\temp\_file';
        }
        else{
            $basepath = '/tmp/_file';
        }
        $fromFile = realpath(__DIR__ . '/../../../storage/test/file.txt');

        $connectionString = 'file://local;path=' . $basepath;
        $builder = new \QzPhp\ConnectionBuilder();
        $conn = $builder->build($connectionString);
        $conn->send($fromFile, $basepath . DIRECTORY_SEPARATOR . '002.txt');
    }

    public function testNoDestination()
    {
        $basepath = '';
        if(strtoupper(substr(PHP_OS, 0, 3)) === 'WIN'){
            $basepath = 'D:\temp\_file';
        }
        else{
            $basepath = '/tmp/_file';
        }
        $fromFile = realpath(__DIR__ . '/../../../storage/test/file.txt');

        $connectionString = 'file://local;path=' . $basepath;
        $builder = new \QzPhp\ConnectionBuilder();
        $conn = $builder->build($connectionString);
        $conn->send($fromFile);
    }
}

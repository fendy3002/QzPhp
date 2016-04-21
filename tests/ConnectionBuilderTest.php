<?php

namespace Tests;

class ConnectionBuilderTest extends \Tests\TestCase
{
    public function testLocalFile()
    {
        $data = [
            [
                'connectionString'=> 'file://local;path=/home/fendy;',
                'path' => '/home/fendy'
            ],
            [
                'connectionString'=> 'file://local;path=/root/etc;',
                'path' => '/root/etc'
            ],
            [
                'connectionString'=> 'file://local;path=/temp/var/;',
                'path' => '/temp/var/'
            ]
        ];
        $builder = new \QzPhp\ConnectionBuilder();
        foreach($data as $each){
            $connection = $builder->build($each['connectionString']);
            $this->assertEquals(\QzPhp\Connection\LocalFileConnection::class, get_class($connection));
            $this->assertEquals($each['path'], $connection->path);
        }
    }

    public function testNetworkFile()
    {
        $data = [
            [
                'connectionString'=> 'file://server001;path=folder1;user=fendy;password=1234;',
                'server' => 'server001',
                'path' => 'folder1',
                'user' => 'fendy',
                'password' => '1234'
            ],
            [
                'connectionString'=> 'file://rome;path=gladiator;user=achilles;password=sword;',
                'server' => 'rome',
                'path' => 'gladiator',
                'user' => 'achilles',
                'password' => 'sword'
            ],
            [
                'connectionString'=> 'file://deathstar;path=darthvader;user=luke;password=skywalker;',
                'server' => 'deathstar',
                'path' => 'darthvader',
                'user' => 'luke',
                'password' => 'skywalker'
            ]
        ];

        $builder = new \QzPhp\ConnectionBuilder();
        foreach($data as $each){
            $connection = $builder->build($each['connectionString']);
            $this->assertEquals(\QzPhp\Connection\NetworkFileConnection::class, get_class($connection));
            $this->assertEquals($each['server'], $connection->server);
            $this->assertEquals($each['path'], $connection->path);
            $this->assertEquals($each['user'], $connection->user);
            $this->assertEquals($each['password'], $connection->password);
        }
    }

    public function testFtp()
    {
        $data = [
            [
                'connectionString'=> 'ftp://server001;user=fendy;password=1234;',
                'server' => 'server001',
                'user' => 'fendy',
                'password' => '1234'
            ],
            [
                'connectionString'=> 'ftp://rome.com;user=achilles;password=sword;',
                'server' => 'rome.com',
                'user' => 'achilles',
                'password' => 'sword'
            ],
            [
                'connectionString'=> 'ftp://deathstar;path=darthvader;user=luke;password=skywalker;timeout=600;',
                'server' => 'deathstar',
                'user' => 'luke',
                'password' => 'skywalker',
                'timeout' => '600'
            ]
        ];

        $builder = new \QzPhp\ConnectionBuilder();
        foreach($data as $each){
            $connection = $builder->build($each['connectionString']);
            $this->assertEquals(\QzPhp\Connection\FtpConnection::class, get_class($connection));
            $this->assertEquals($each['server'], $connection->server);
            $this->assertEquals($each['user'], $connection->user);
            $this->assertEquals($each['password'], $connection->password);
        }
    }
}

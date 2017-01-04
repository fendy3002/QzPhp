<?php
namespace Test\Connection;

class LocalFileConnectionTest extends \Tests\TestCase
{
    public function test()
    {
        $path = '/tmp/_no_exists_folder';
        $localFileConnection = new \QzPhp\Connection\LocalFileConnection((object)[
            'path' => $path
        ]);

        $localFile = '/tmp/001.txt';
        file_put_contents($localFile, 'A');
        $result = $localFileConnection->send('/tmp/001.txt');

        $this->assertEquals(true, file_exists($path . '/' . '001.txt'));
        $this->assertEquals($result->data(), $path . '/001.txt');
        unlink($path . '/' . '001.txt');
        rmdir($path);
        unlink($localFile);
    }
}

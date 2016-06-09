<?php

namespace Tests;

class FolderSizeTest extends \Tests\TestCase
{
    public function testFolder()
    {
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $path = 'D:\temp';
        }
        else{
            $path = '\tmp';
        }
        $io = new \QzPhp\IO();
        $size = $io->getFolderSize($path);
        $this->assertGreaterThanOrEqual(0, $size);
    }
}

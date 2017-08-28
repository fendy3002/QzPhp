<?php
namespace Test\IO;

class FileTest extends \Tests\TestCase
{
    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testWriteRead()
    {
        $io = new \QzPhp\IO();
        $filePath = $io->combine("storage", "test", "file2.txt");
        
        $io->touch($filePath);
        $data = "Government of the people, by the people, for the people, shall not perish from the Earth.";
        $io->writeFile($filePath, $data);

        $actual = $io->readFile($filePath);
        $this->assertEquals($actual, $data);

        $io->deleteFile($filePath);
        $fileExists = $io->fileExists($filePath);
        $this->assertEquals(false, $fileExists);
    }
}

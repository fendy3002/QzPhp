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
        $folderPath = $io->combine("storage", "test");
        $filePath = $io->combine($folderPath, "file2.txt");
        
        $io->touch($filePath);
        $data = "Government of the people, by the people, for the people, shall not perish from the Earth.";
        $io->writeFile($filePath, $data);

        $actual = $io->readFile($filePath);
        $this->assertEquals($actual, $data);

        $scanned = $io->scandir($folderPath);
        $this->assertGreaterThan(0, count($scanned));

        $io->deleteFile($filePath);
        $fileExists = $io->fileExists($filePath);
        $this->assertEquals(false, $fileExists);
    }
}

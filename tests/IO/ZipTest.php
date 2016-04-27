<?php
namespace Test\IO;

class ZipTest extends \Tests\TestCase
{
    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function test()
    {
        $io = new \QzPhp\IO();
        $d = DIRECTORY_SEPARATOR;
        $storage = __DIR__ . $d . '..' . $d . '..' . $d . 'storage';
        $folder = $storage . $d . 'fromZip';
        $extractTo = $storage . $d . 'toZip';
        $file = $folder . $d . 'file.txt';
        $extractedFile = $extractTo . $d . 'file.txt';
        $zipFile = $storage . $d . 'zip.zip';
        mkdir($folder, 0777, true);
        touch($file);
        chmod($file, 0777);

        $io->zip($folder, $zipFile);
        $io->unzip($zipFile, $extractTo);
        $this->assertFileExists($extractedFile);

        unlink($file);
        unlink($extractedFile);
        rmdir($folder);
        rmdir($extractTo);
        unlink($zipFile);
    }
}

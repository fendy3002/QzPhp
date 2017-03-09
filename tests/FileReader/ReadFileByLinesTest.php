<?php
namespace Test\IO;

class ReadFileByLinesTest extends \Tests\TestCase
{
    public function test()
    {
        $fileReader = new \QzPhp\FileReader();
        $d = DIRECTORY_SEPARATOR;
        $filepath = __DIR__ . $d . '..' . $d . '..' . $d . 'storage' . $d . 'test' . $d . 'file.txt';

        $startPos = 0;
        $limit = 5;
        $result = $fileReader->readFileByLines($filepath, $startPos, $limit);

        $expectedPos = 115;
        $expectedContent = 'line 1 field 2 field 3' . "\n" .
            'line 2 field 2 field 3' . "\n" .
            'line 3 field 2 field 3' . "\n" .
            'line 4 field 2 field 3' . "\n" .
            'line 5 field 2 field 3';
        $this->assertEquals($expectedPos, $result->pos);
        $this->assertEquals($expectedContent, $result->content);
    }

    public function testOffset()
    {
        $fileReader = new \QzPhp\FileReader();
        $d = DIRECTORY_SEPARATOR;
        $filepath = __DIR__ . $d . '..' . $d . '..' . $d . 'storage' . $d . 'test' . $d . 'file.txt';

        $startPos = 115;
        $limit = 5;
        $result = $fileReader->readFileByLines($filepath, $startPos, $limit);

        $expectedPos = 231;
        $expectedContent = 'line 6 field 2 field 3' . "\n" .
           'line 7 field 2 field 3' . "\n" .
           'line 8 field 2 field 3' . "\n" .
           'line 9 field 2 field 3' . "\n" .
           'line 10 field 2 field 3';
        $this->assertEquals($expectedPos, $result->pos);
        $this->assertEquals($expectedContent, $result->content);
    }

    public function testOver()
    {
        $fileReader = new \QzPhp\FileReader();
        $d = DIRECTORY_SEPARATOR;
        $filepath = __DIR__ . $d . '..' . $d . '..' . $d . 'storage' . $d . 'test' . $d . 'file.txt';

        $startPos = 0;
        $limit = 1000;
        $result = $fileReader->readFileByLines($filepath, $startPos, $limit);

        $expectedPos = 712;
        $expectedContent = file_get_contents($filepath);
        $this->assertEquals($expectedPos, $result->pos);
        $this->assertEquals($expectedContent, $result->content);
    }


    public function testR()
    {
        $fileReader = new \QzPhp\FileReader();
        $d = DIRECTORY_SEPARATOR;
        $filepath = __DIR__ . $d . '..' . $d . '..' . $d . 'storage' . $d . 'test' . $d . 'file.txt';

        $startPos = 0;
        $limit = 5;
        $result = $fileReader->readFileByLinesR($filepath, $startPos, $limit);

        $expectedPos = 99;
        $expectedContent = 'line 27 field 2 field 3' . "\n" .
            'line 28 field 2 field 3' . "\n" .
            'line 29 field 2 field 3' . "\n" .
            'line 30 field 2 field 3' . "\n" .
            '';
        $this->assertEquals($expectedPos, $result->pos);
        $this->assertEquals($expectedContent, $result->content);
    }

    public function testROffset()
    {
        $fileReader = new \QzPhp\FileReader();
        $d = DIRECTORY_SEPARATOR;
        $filepath = __DIR__ . $d . '..' . $d . '..' . $d . 'storage' . $d . 'test' . $d . 'file.txt';

        $startPos = 99;
        $limit = 5;
        $result = $fileReader->readFileByLinesR($filepath, $startPos, $limit);

        $expectedPos = 219;
        $expectedContent = 'line 22 field 2 field 3' . "\n" .
            'line 23 field 2 field 3' . "\n" .
            'line 24 field 2 field 3' . "\n" .
            'line 25 field 2 field 3' . "\n" .
            'line 26 field 2 field 3';
        $this->assertEquals($expectedPos, $result->pos);
        $this->assertEquals($expectedContent, $result->content);
    }
}

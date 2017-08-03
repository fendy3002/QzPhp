<?php

namespace Tests\String;

class ReplaceAccentTest extends \Tests\TestCase
{
    public function testReplace()
    {
        $string = new \QzPhp\Str();
        $source = 'Heßdorf';

        $result = $string->replaceAccent($source);
        $expected = 'Hessdorf';
        $this->assertEquals($expected, $result);
    }
}

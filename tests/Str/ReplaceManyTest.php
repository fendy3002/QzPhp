<?php

namespace Tests\String;

class ReplaceManyTest extends \Tests\TestCase
{
    public function testReplace()
    {
        $string = new \QzPhp\Str();
        $source = 'The quick <brown> {fox} (jumps) over the <lazy> dog';
        $matches = [
            '<brown>' => 'black',
            '{fox}' => 'bear',
            '(jumps)' => 'crawls',
            '<lazy>' => 'smart'
        ];

        $result = $string->replaceMany($source, $matches);
        $expected = 'The quick black bear crawls over the smart dog';
        $this->assertEquals($expected, $result);
    }
}

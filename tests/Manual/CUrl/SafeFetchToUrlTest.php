<?php

namespace Tests\Manual\CUrl;

class SafeFetchToUrlTest extends \Tests\TestCase
{
    public function testFetch()
    {
        $from = 'http://i.imgur.com/etjgJ2D.jpg';
        $to = __DIR__ . '/../../../storage/temp/dickbutt.jpg';
        $curl = new \QzPhp\CUrl($from);
        $curl->submitToFile($to);
    }
}

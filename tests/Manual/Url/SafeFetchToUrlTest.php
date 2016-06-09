<?php

namespace Tests;

class SafeFetchToUrlTest extends \Tests\TestCase
{
    public function testFetch()
    {
        $from = 'http://i.imgur.com/etjgJ2D.jpg';
        $to = __DIR__ . '/../../../storage/temp/dickbutt.jpg';
        $url = new \QzPhp\Url();
        $url->safeFetchToFile($from, $to);
    }
}

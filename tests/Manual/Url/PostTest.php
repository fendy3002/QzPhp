<?php

namespace Tests;

class SafeFetchToUrlTest extends \Tests\TestCase
{
    public function testFetch()
    {
        $postdata = 'col1=val1&col2=val2&col3=val3';
        $target = 'http://httpbin.org/post';
        $url = new \QzPhp\Url();
        $response = $url->post($target, $postdata);

        print_r($response);
    }
}

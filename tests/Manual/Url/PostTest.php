<?php

namespace Tests\Manual\Url;

class SafeFetchToUrlTest extends \Tests\TestCase
{
    public function testPost()
    {
        $postdata = 'col1=val1&col2=val2&col3=val3';
        $target = 'http://httpbin.org/post';
        $url = new \QzPhp\Url();
        $response = $url->post($target, $postdata);

        print_r($response);
    }
}

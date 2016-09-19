<?php

namespace Tests\Manual\CUrl;

class SafeFetchToUrlTest extends \Tests\TestCase
{
    public function testPost()
    {
        $postdata = [
            'col1' => 'val1',
            'col2' => 'val2',
            'col3' => 'val3'
        ];
        $target = 'http://httpbin.org/post';
        $curl = new \QzPhp\CUrl($target);
        $curl->addPostDataMany($postdata);
        $response = $curl->submit($postdata);

        print_r($response);
    }
}

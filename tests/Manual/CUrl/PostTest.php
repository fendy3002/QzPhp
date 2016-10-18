<?php

namespace Tests\Manual\CUrl;

class PostTest extends \Tests\TestCase
{
    public function testPost()
    {
        $postdata = [
            'col1' => 'val1',
            'col2' => 'val2',
            'col3' => 'val3',
            'col4' => http_build_query(['val1', 'val2'])
        ];
        $target = 'http://httpbin.org/post';
        $curl = new \QzPhp\CUrl($target);
        $curl->addPostDataMany($postdata);
        $response = $curl->submit($postdata);

        print_r($response);
    }
}

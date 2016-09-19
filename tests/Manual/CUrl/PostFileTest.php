<?php

namespace Tests\Manual\CUrl;

class PostFileTest extends \Tests\TestCase
{
    public function testPost()
    {
        $from = __DIR__ . '/../../../storage/test/file.txt';
        $postdata = [
            'col1' => 'val1',
            'col2' => 'val2',
            'col3' => 'val3'
        ];
        $target = 'http://httpbin.org/post';
        $curl = new \QzPhp\CUrl($target);
        $curl->addPostDataMany($postdata);
        $curl->addFile($from);
        $response = $curl->submit($postdata);

        print_r($response);
    }
}

<?php
namespace QzPhp;

class Url{
    public function safeFetch($url){
        $context = stream_context_create(array(
            'http' => array('ignore_errors' => true),
        ));

        $content = file_get_contents($url, false, $context);
        $header = $http_response_header;
        $code = substr($header[0], 9, 3);
        $result = (object)[
            'content' => $content,
            'code' => $code,
            'header' => $header
        ];
        return $result;
    }
}

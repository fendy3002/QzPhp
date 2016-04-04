<?php
namespace QzPhp;

class Url{
    public function safeFetch($url){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);

        // in real life you should use something like:
        // curl_setopt($ch, CURLOPT_POSTFIELDS,
        //          http_build_query(array('postvar1' => 'value1')));

        // receive server response ...
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FORBID_REUSE, true);

        $content = curl_exec ($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close ($ch);
        return (object)[
            'content' => $content,
            'code' => $code
        ];
    }
}
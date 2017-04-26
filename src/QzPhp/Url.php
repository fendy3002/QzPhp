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
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $content = curl_exec ($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        $error = curl_error($ch);
        $errno = curl_errno($ch);

        curl_close ($ch);
        return (object)[
            'content' => $content,
            'code' => $code,
            'error' => $error,
            'errno' => $errno
        ];
    }

    public function post($url, $postdata){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);

        curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FORBID_REUSE, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $content = curl_exec ($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        $error = curl_error($ch);
        $errno = curl_errno($ch);
        
        curl_close ($ch);
        return (object)[
            'content' => $content,
            'code' => $code,
            'error' => $error,
            'errno' => $errno
        ];
    }

    public function safeFetchToFile($url, $file){
        $fp = fopen ($file, 'w+');

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);

        // in real life you should use something like:
        // curl_setopt($ch, CURLOPT_POSTFIELDS,
        //          http_build_query(array('postvar1' => 'value1')));

        // receive server response ...
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_FORBID_REUSE, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        $errno = curl_errno($ch);

        curl_close ($ch);
        return (object)[
            'code' => $code,
            'error' => $error,
            'errno' => $errno
        ];
    }
}

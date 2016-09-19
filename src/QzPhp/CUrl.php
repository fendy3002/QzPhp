<?php
namespace QzPhp;

class CUrl{
    public function __construct($url){
        $this->url = $url;
    }

    private $files = [];
    private $postdata = [];

    private $url;
    public $isPost = false;
    public $sslVerify = false;

    public function addFile($filename, $postname = NULL, $mimetype = NULL){
        $mimetype = $mimetype ?: mime_content_type($filename);
        $postname = $postname ?: basename($filename);
        $this->files[] = (object)['filename' => $filename,
            'postname' => $postname, 'mimetype' => $mimetype];
    }
    public function addPostData($key, $value){
        $this->postdata[$key] = $value;
    }
    public function addPostDataMany($postdata){
        $this->postdata = array_merge($this->postdata, $postdata);
    }

    public function submit(){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_POST, $this->isPost);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FORBID_REUSE, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $this->sslVerify);

        $postdata = $this->getPostData($this->postdata, $this->files);
        if(count($postdata) > 0){
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
        }

        $content = curl_exec ($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close ($ch);
        return (object)[
            'content' => $content,
            'code' => $code
        ];
    }

    public function submitToFile($file){
        $fp = fopen ($file, 'w+');

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->url);
        // in real life you should use something like:

        // curl_setopt($ch, CURLOPT_POSTFIELDS,
        //          http_build_query(array('postvar1' => 'value1')));

        // receive server response ...
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_FORBID_REUSE, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $this->sslVerify);

        $postdata = $this->getPostData($this->postdata, $this->files);
        if(count($postdata) > 0){
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
        }

        curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close ($ch);
        return (object)[
            'code' => $code
        ];
    }

    private function getPostData($postdata, $files){
        $result = array_merge($postdata, []);

        foreach($files as $eachFile){
            $curlFile = new \CURLFile($eachFile->filename,
                $eachFile->mimetype,
                $eachFile->postname);
            $result[$eachFile->postname] = $curlFile;
        }
        return $result;
    }
}

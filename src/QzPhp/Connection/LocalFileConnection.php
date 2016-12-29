<?php

namespace QzPhp\Connection;
use Session;
use QzPhp\Q;

class LocalFileConnection
{
    public function __construct($setting){
        $this->path = $setting->path;
    }
    public $path;

    public function send($localFile, $toFile = NULL){
        $path = rtrim($this->path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        $toFile = !empty($toFile) ? $toFile : $path . basename($localFile);
        $toFolder = Q::Z()->io()->directoryOf($toFile);
        if(!file_exists($toFolder)){
            mkdir($toFolder, 0777, true);
        }

        copy($localFile, $toFile);
    }
}

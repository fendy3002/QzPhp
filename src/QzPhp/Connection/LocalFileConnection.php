<?php

namespace QzPhp\Connection;
use Session;

class LocalFileConnection
{
    public function __construct($setting){
        $this->path = $setting->path;
    }
    public $path;

    public function send($localFile, $toFile = NULL){
        $path = rtrim($this->path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        $toFile = !empty($toFile) ? $toFile : $path . basename($localFile);
        copy($localFile, $toFile);
    }
}

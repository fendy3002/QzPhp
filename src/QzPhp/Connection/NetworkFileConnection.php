<?php

namespace QzPhp\Connection;
use Session;

class NetworkFileConnection
{
    public function __construct($setting){
        $this->server = $setting->server;
        $this->path = $setting->path;
        if(!empty($setting->user)){
            $this->user = $setting->user;
        }
        if(!empty($setting->password)){
            $this->password = $setting->password;
        }
    }
    public $server;
    public $path;
    public $user;
    public $password;

    public function send($localFile, $toFile = NULL){
        // pending
    }
}

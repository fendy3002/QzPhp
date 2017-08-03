<?php

namespace QzPhp;

class ConnectionBuilder
{
    public function __construct(){

    }
    public function build($connectionString){
        if(strpos($connectionString, 'file://local') === 0){
            $params = substr($connectionString, 13);
            $setting = (object)$this->paramsToKeyValueArray($params);
            $result = new \QzPhp\Connection\LocalFileConnection($setting);
            return $result;
        }
        else if(strpos($connectionString, 'file://') === 0){
            $url = substr($connectionString, 0, strpos($connectionString, ';'));
            $server = substr($url, 7);
            $params = substr($connectionString, strpos($connectionString, ';') + 1);
            $setting = (object)$this->paramsToKeyValueArray($params);
            $setting->server = $server;
            $result = new \QzPhp\Connection\NetworkFileConnection($setting);
            return $result;
        }
        else if(strpos($connectionString, 'ftp://') === 0){
            $url = substr($connectionString, 0, strpos($connectionString, ';'));
            $server = substr($url, 6);
            $params = substr($connectionString, strpos($connectionString, ';') + 1);
            $setting = (object)$this->paramsToKeyValueArray($params);
            $setting->server = $server;
            $result = new \QzPhp\Connection\FtpConnection($setting);
            return $result;
        }
    }

    public function paramsToKeyValueArray($params){
        $settings = array_filter(explode(';', $params));
        $keyValueSetting = [];
        foreach($settings as $setting){
            $keyValue = explode('=', $setting);
            $keyValueSetting[$keyValue[0]] = $keyValue[1];
        }
        return $keyValueSetting;
    }
}

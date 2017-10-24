<?php
namespace QzPhp\Cache;


class RedisCache{
    public function __construct($key, $option = NULL){
        $this->key = $key;
        if(!empty($option)){
            if(array_key_exists('expire', $option)){
                $this->expire = $option['expire'];
            }
            if(array_key_exists('connection', $option)){
                $this->connection = $option['connection'];
            }
            if(array_key_exists('onExpire', $option)){
                $this->onExpire = $option['onExpire'];
            }
        }

        $this->connection = $this->connection ?: [];
        $this->expire = $this->expire ?: 300; // 5 minute
    }

    private $key;
    private $expire;
    private $onExpire;
    private $connection;
    private $lastUpdate;

    public function get($onExpire = NULL){
        $onExpire = $onExpire ?: $this->onExpire;
        $client = new \Predis\Client($this->connection);
        
        if($this->isExpired()){
            $value = $onExpire();
            $toCache = serialize($value);

            $time = time();
            $client->set($this->key, $toCache);
            $this->lastUpdate = $time;

            return $value;
        }
        else{
            $fromCache = $client->get($this->key);
            return unserialize($fromCache);
        }
    }

    private function isExpired(){
        return empty($this->lastUpdate) || (time() - $this->lastUpdate) > $this->expire;
    }
}
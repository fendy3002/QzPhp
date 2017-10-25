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
        $this->client = new \Predis\Client($this->connection);
    }

    private $key;
    private $expire;
    private $onExpire;
    private $connection;
    private $client;

    public function get($onExpire = NULL){
        $onExpire = $onExpire ?: $this->onExpire;
        
        if($this->isExpired()){
            $value = $onExpire();
            $toCache = serialize($value);

            $time = time();
            $this->client->set($this->key, $toCache);
            $this->client->set($this->key . '__lastupdate', $time);

            return $value;
        }
        else{
            $fromCache = $this->client->get($this->key);
            return unserialize($fromCache);
        }
    }
    public function reseed($onExpire = NULL){
        $this->client->set($this->key . '__lastupdate', null);
        return $this->get($onExpire);
    }

    private function isExpired(){
        $lastUpdate = $this->client->get($this->key . '__lastupdate');
        return empty($lastUpdate) || (time() - $lastUpdate) > $this->expire;
    }
}
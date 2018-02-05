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
            if(array_key_exists('onReseed', $option)){
                $this->onReseed = $option['onReseed'];
            }
            if(array_key_exists('refreshOnGet', $option)){
                $this->refreshOnGet = $option['refreshOnGet'];
            }
        }

        $this->connection = $this->connection ?: [];
        $this->expire = $this->expire ?: 300; // 5 minute
        $this->refreshOnGet = $this->refreshOnGet ?: false;
        $this->client = new \Predis\Client($this->connection);
    }

    public function __destruct(){
        unset($this->client);
    }

    private $key;
    private $refreshOnGet;
    private $expire;
    private $onExpire;
    private $onReseed;
    private $connection;
    private $client;

    public function get($onExpire = NULL){
        $onExpire = $onExpire ?: $this->onExpire;
        $fromCache = $this->client->get($this->key);
        
        if($this->isExpired()){
            $value = null;
            if(!empty($fromCache)){
                $fromCacheUnserialized = unserialize($fromCache);
                $value = $onExpire($fromCacheUnserialized);
            }
            else{
                $value = $onExpire(null);
            }
            $toCache = serialize($value);

            $time = time();
            $this->client->set($this->key, $toCache);
            $this->client->set($this->key . '__lastupdate', $time);

            return $value;
        }
        else{
            if($this->refreshOnGet){
                $time = time();
                $this->client->set($this->key . '__lastupdate', $time);
            }
            $fromCacheUnserialized = unserialize($fromCache);
            return $fromCacheUnserialized;
        }
    }
    public function reseed($onExpire = NULL, $onReseed = NULL){
        $onReseed = $onReseed ?: $this->onReseed;
        
        if(!empty($onReseed)){
            $fromCache = $this->client->get($this->key);
            if(!empty($fromCache)){
                $fromCacheUnserialized = unserialize($fromCache);
                $onReseed($fromCacheUnserialized);
            }
            else{
                $onReseed(null);
            }
        }
        $this->client->set($this->key . '__lastupdate', null);
        return $this->get($onExpire);
    }

    private function isExpired(){
        $lastUpdate = $this->client->get($this->key . '__lastupdate');
        return empty($lastUpdate) || (time() - $lastUpdate) > $this->expire;
    }
}
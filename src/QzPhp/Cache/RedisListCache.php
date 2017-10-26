<?php
namespace QzPhp\Cache;


class RedisListCache{
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
        $this->lastUpdate = [];
        $this->client = new \Predis\Client($this->connection);
    }
    
    public function __destruct(){
        unset($this->client);
    }

    private $key;
    private $expire;
    private $onExpire;
    private $connection;

    public function get($key, $onExpire = NULL){
        $onExpire = $onExpire ?: $this->onExpire;

        $redisKey = $this->key. '.' . $key;
        
        if($this->isExpired($redisKey)){
            $value = $onExpire($key);
            $toCache = serialize($value);

            $time = time();
            $this->client->set($redisKey, $toCache);
            $this->client->set($redisKey . '__lastupdate', $time);

            return $value;
        }
        else{
            $fromCache = $this->client->get($redisKey);
            return unserialize($fromCache);
        }
    }
    public function reseed($key, $onExpire = NULL){
        $redisKey = $this->key. '.' . $key;
        $this->client->set($redisKey . '__lastupdate', null);
        return $this->get($key, $onExpire);
    }
    private function isExpired($key){
        $lastUpdate = $this->client->get($key . '__lastupdate');
        return empty($lastUpdate) || (time() - $lastUpdate) > $this->expire;
    }
}
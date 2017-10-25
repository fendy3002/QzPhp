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
    }

    private $key;
    private $expire;
    private $onExpire;
    private $connection;
    private $lastUpdate;

    public function get($key, $onExpire = NULL){
        $onExpire = $onExpire ?: $this->onExpire;

        $redisKey = $this->key. '.' . $key;
        $client = new \Predis\Client($this->connection);
        
        if($this->isExpired($redisKey)){
            $value = $onExpire($key);
            $toCache = serialize($value);

            $time = time();
            $client->set($redisKey, $toCache);
            $this->lastUpdate[$redisKey] = $time;

            return $value;
        }
        else{
            $fromCache = $client->get($redisKey);
            return unserialize($fromCache);
        }
    }
    private function isExpired($key){
        return !array_key_exists($key, $this->lastUpdate) || (time() - $this->lastUpdate[$key]) > $this->expire;
    }
}
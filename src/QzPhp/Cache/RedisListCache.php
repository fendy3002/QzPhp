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
    }

    private $key;
    private $expire;
    private $onExpire;
    private $connection;
    private $lastUpdate;

    public function get($key, $onExpire = NULL){
        $onExpire = $onExpire ?: $this->onExpire;
        $expireHandler = function() use($key, $onExpire){
            return $onExpire($key);
        };
        $client = new \Predis\Client($this->connection);
        
        $fromCache = $client->get($this->key. '.' . $key);
        $expirable = NULL;
        if(empty($fromCache)){
            $expirable = new \QzPhp\NullExpirable($this->expire);
        } else{
            $expirable = unserialize($fromCache);
        }
        $result = $expirable->get($expireHandler);
        $toCache = serialize($expirable);

        $client->set($this->key. '.' . $key, $toCache);
        return $result;
    }
}
<?php
namespace Test\Cache;

class TestModel{
    public $name = "Hello";
    public $address = "World";
    public $city = "";
    public $counter = 0;
}

class RedisCacheTest extends \Tests\TestCase
{
    public function testExpireOnMethod()
    {
        $conf = include(__DIR__ . "/../../testconf.php");
        $redisConnection = $conf['redis'];

        $expirable = new \QzPhp\Cache\RedisCache("redis.cache", [
            "connection" => $redisConnection,
            "expire" => 3
        ]);
        $context = (object)[
            "cacheCall" => 0
        ];

        $onExpire = function() use($context){
            $context->cacheCall++;
            return [
                new TestModel()
            ];
        };
        $value1 = $expirable->get($onExpire);
        $value2 = $expirable->get($onExpire);
        sleep(4);
        $value3 = $expirable->get($onExpire);
        sleep(1);
        $value4 = $expirable->reseed($onExpire);
        sleep(1);
        $value5 = $expirable->get($onExpire);
        
        $this->assertEquals(3, $context->cacheCall);
        $this->assertEquals($value1, $value2);
        $this->assertEquals($value2, $value3);
        $this->assertEquals($value3, $value4);
        $this->assertEquals($value4, $value5);

        $redis = new \Predis\Client($redisConnection);
        $redis->flushAll();
    }
    public function testExpireOnConstructor()
    {
        $conf = include(__DIR__ . "/../../testconf.php");
        $redisConnection = $conf['redis'];
        $context = (object)[
            "cacheCall" => 0
        ];
        $expirable = new \QzPhp\Cache\RedisCache("redis.cache", [
            "connection" => $redisConnection,
            "expire" => 3,
            "onExpire" => function() use($context){
                $context->cacheCall++;
                return [
                    new TestModel()
                ];
            }
        ]);
        $value1 = $expirable->get();
        $value2 = $expirable->get();
        sleep(4);
        $value3 = $expirable->get();

        $this->assertEquals(2, $context->cacheCall);
        $this->assertEquals($value1, $value2);
        $this->assertEquals($value2, $value3);

        $redis = new \Predis\Client($redisConnection);
        $redis->flushAll();
    }
    public function testRefreshOnGet()
    {
        $conf = include(__DIR__ . "/../../testconf.php");
        $redisConnection = $conf['redis'];
        $context = (object)[
            "cacheCall" => 0
        ];
        $expirable = new \QzPhp\Cache\RedisCache("redis.cache", [
            "connection" => $redisConnection,
            "expire" => 3,
            "onExpire" => function() use($context){
                $context->cacheCall++;
                return [
                    new TestModel()
                ];
            },
            "refreshOnGet" => true
        ]);
        $value1 = $expirable->get();
        sleep(1);
        $value2 = $expirable->get();
        sleep(2);
        $value3 = $expirable->get();
        sleep(1);
        $value4 = $expirable->get();

        $this->assertEquals(1, $context->cacheCall);
        $this->assertEquals($value1, $value2);
        $this->assertEquals($value2, $value3);
        $this->assertEquals($value3, $value4);

        $redis = new \Predis\Client($redisConnection);
        $redis->flushAll();
    }
    public function testOnReseedAndLastCache()
    {
        $conf = include(__DIR__ . "/../../testconf.php");
        $redisConnection = $conf['redis'];

        $context = (object)[
            "cacheCall" => 0,
            "reseedCall" => 0
        ];
        $expirable = new \QzPhp\Cache\RedisCache("redis.cache", [
            "connection" => $redisConnection,
            "onReseed" => function() use($context){
                $context->reseedCall++;
            },
            "onExpire" => function($oldValue) use($context){
                $context->cacheCall++;
                $result = new TestModel();
                if(!empty($oldValue)){
                    $result->counter = $oldValue->counter + 1;
                }
                return $result;
            },
            "expire" => 3
        ]);
        $value1 = $expirable->get($onExpire);
        $value2 = $expirable->get($onExpire);
        sleep(4);
        $value3 = $expirable->get($onExpire);
        sleep(1);
        $value4 = $expirable->reseed($onExpire);
        sleep(1);
        $value5 = $expirable->get($onExpire);
        
        $this->assertEquals(3, $context->cacheCall);
        $this->assertEquals(0, $value1->counter);
        $this->assertEquals(0, $value2->counter);
        $this->assertEquals(1, $value3->counter);
        $this->assertEquals(2, $value4->counter);
        $this->assertEquals(2, $value5->counter);

        $redis = new \Predis\Client($redisConnection);
        $redis->flushAll();
    }
}

<?php
namespace Test\Cache;

class TestModel{
    public $name = "Hello";
    public $address = "World";
    public $city = "";
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
}

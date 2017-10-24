<?php
namespace Test\Expirable;

class TestModel{
    public $name = "Hello";
    public $address = "World";
    public $city = "";
}

class RedisCacheTest extends \Tests\TestCase
{
    public function testExpireOnMethod()
    {
        $redisConnection = [
            'scheme' => 'tcp',
            'host'   => 'redis_tst',
            'port'   => 6379
        ];
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

        $this->assertEquals(2, $context->cacheCall);
        $this->assertEquals($value1, $value2);
        $this->assertEquals($value2, $value3);

        $redis = new \Predis\Client($redisConnection);
        $redis->flushAll();
    }
    public function testExpireOnConstructor()
    {
        $redisConnection = [
            'scheme' => 'tcp',
            'host'   => 'redis_tst',
            'port'   => 6379
        ];
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
        $value1 = $expirable->get($onExpire);
        $value2 = $expirable->get($onExpire);
        sleep(4);
        $value3 = $expirable->get($onExpire);

        $this->assertEquals(2, $context->cacheCall);
        $this->assertEquals($value1, $value2);
        $this->assertEquals($value2, $value3);

        $redis = new \Predis\Client($redisConnection);
        $redis->flushAll();
    }
}

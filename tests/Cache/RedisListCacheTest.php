<?php
namespace Test\Cache;

class TestListModel{
    public $name = "Hello";
    public $address = "World";
    public $city = "";
}

class RedisListCacheTest extends \Tests\TestCase
{
    public function testExpireOnMethod()
    {
        $conf = include(__DIR__ . "/../../testconf.php");
        $redisConnection = $conf['redis'];
        $expirable = new \QzPhp\Cache\RedisListCache("redis.cache", [
            "connection" => $redisConnection,
            "expire" => 3
        ]);
        $context = (object)[
            "cacheCall" => []
        ];

        $onExpire = function($key) use($context){
            if(empty($context->cacheCall[$key])){
                $context->cacheCall[$key] = 0;
            }
            $context->cacheCall[$key]++;
            return [
                new TestListModel()
            ];
        };
        $value1 = $expirable->get('Luke', $onExpire);
        $value1_1 = $expirable->get('Luke', $onExpire);
        $value2 = $expirable->get('Anakin', $onExpire);
        sleep(4);
        $value1 = $expirable->get('Luke', $onExpire);
        $value1_1 = $expirable->get('Luke', $onExpire);
        $value2 = $expirable->get('Anakin', $onExpire);
        $value2 = $expirable->reseed('Anakin', $onExpire);
        $value2 = $expirable->get('Anakin', $onExpire);

        $this->assertEquals(2, $context->cacheCall['Luke']);
        $this->assertEquals(3, $context->cacheCall['Anakin']);

        $redis = new \Predis\Client($redisConnection);
        $redis->flushAll();
    }
}

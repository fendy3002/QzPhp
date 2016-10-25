<?php
namespace Test\Expirable;

class ExpirableTest extends \Tests\TestCase
{
    public function test()
    {
        $expirable = new \QzPhp\Expirable(1, 2);
        $onExpire = function(){
            return 3;
        };
        $valueBefore = $expirable->get($onExpire);
        sleep(3);
        $valueAfter = $expirable->get($onExpire);

        $this->assertEquals(1, $valueBefore);
        $this->assertEquals(3, $valueAfter);
    }
}

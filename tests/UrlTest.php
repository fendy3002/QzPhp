<?php

class UrlTest extends \Tests\TestCase
{
    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testSafeFetch()
    {
        $fetched = \QzPhp\Q::Z()->url()->safeFetch('http://httpstat.us/200');
        $this->assertEquals('200', $fetched->code);
        $this->assertEquals('200 OK', $fetched->content);

        $fetched = \QzPhp\Q::Z()->url()->safeFetch('http://httpstat.us/404');
        $this->assertEquals('404', $fetched->code);
        $this->assertEquals('404 Not Found', $fetched->content);
    }
}

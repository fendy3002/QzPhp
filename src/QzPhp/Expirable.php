<?php
namespace QzPhp;

class Expirable extends NullExpirable {
    public function __construct($value, $expire = 300){
        $this->value = $value;
        $this->expire = $expire;
        $this->lastUpdate = time();
    }
}

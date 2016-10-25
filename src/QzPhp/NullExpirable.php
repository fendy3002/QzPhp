<?php
namespace QzPhp;

class NullExpirable{
    public function __construct($expire = 300){
        $this->expire = $expire;
    }
    protected $value;
    protected $lastUpdate;
    protected $expire;

    public function get($onExpire){
        if($this->isExpire()){
            $this->value = $onExpire();
            $this->lastUpdate = time();
        }
        return $this->value;
    }
    private function isExpire(){
        return empty($this->lastUpdate) || (time() - $this->lastUpdate) > $this->expire;
    }
}

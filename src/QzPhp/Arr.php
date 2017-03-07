<?php
namespace QzPhp;

class Arr{
    public function __construct($arr){
        $this->arr = $arr;
    }
    protected $arr;

    public function get($key){
        return array_key_exists($key, $this->arr) ? $this->arr[$key] : NULL;
    }
    public function has($key){
        return array_key_exists($key, $this->arr);
    }
    public function set($key, $value){
        $this->arr[$key] = $value;
        return $this;
    }
    public function merge(){
        $result = array_merge([], $this->arr);

        foreach (func_get_args() as $param) {
            $result = array_merge($result, $param);
        }

        $this->arr = $result;
        return $result;
    }

    public function asArray(){
        return $this->arr;
    }
    public function asObject($deep = false){
        if($deep){ 
            return json_decode(json_encode($this->arr)); 
        }
        else{
            return (object)$this->arr;
        }
    }
}
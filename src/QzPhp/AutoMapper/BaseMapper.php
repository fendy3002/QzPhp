<?php

namespace QzPhp\AutoMapper;
use QzPhp\Q;
use QzPhp\Linq;

class BaseMapper
{
    protected function __construct($config){
        $this->config = $config;
    }
    protected $config;

    protected function date($from, $format = ""){
        return date($format ?: $this->config['dateFormat'], strtotime($from));
    }
    protected function datetime($from, $format = ""){
        return date($format ?: $this->config['dateTimeFormat'], strtotime($from));
    }
    protected function int($from){
        return (int)$from;
    }
    protected function bool($from){
        return $from == "1" ? true :
            $from == "0" ? false :
            strtolower($from) == "true" ? true : 
            strtolower($from) == "false" ? false :
            (bool)$from;
    }
}
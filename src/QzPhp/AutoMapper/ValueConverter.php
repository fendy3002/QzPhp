<?php

namespace QzPhp\AutoMapper;
use QzPhp\Q;
use QzPhp\Linq;

class ValueConverter
{
    public function __construct($type, $value){
        $this->type = $type;
        $this->value = $value;
    }
    public $type;
    public $value;

    public function convert($data){
        $field = $this->value;
        return Linq::select($data, function($k) use($field){
            return $k->$field;
        });
    }
}

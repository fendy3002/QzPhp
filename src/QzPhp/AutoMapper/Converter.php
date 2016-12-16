<?php

namespace QzPhp\AutoMapper;

class Converter
{
    public function __construct($schema, $as){
        $this->schema = $schema;
        $this->as = $as;
    }
    public $schema;
    public $as;

    public function fromObject($data, $additionals = []){

    }
    public function fromArray($data, $additionals = []){

    }
}

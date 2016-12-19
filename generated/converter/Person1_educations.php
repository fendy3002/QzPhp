<?php
namespace Generated\Converter;

use QzPhp\Linq;
use QzPhp\AutoMapper;

class Person1_educations{
    public function __construct() {
        
    }


    public function convert($data) {
        return Linq::select($data, function($k){
            $result = (object)[];
            $result->year = $k->year;
            $result->organization = $k->organization;
        
            return $result;
        });
    }
}

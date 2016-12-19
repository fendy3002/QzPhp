<?php
namespace Generated\Converter;

use QzPhp\Linq;

class Address1{
    public function __construct() {
        
    }


    public function convert($data, $additional) {
        return Linq::select($data, function($k) use($additional){
            $result = new \Models\Address();
            $result->streetName = $k->streetName;
            $result->city = $k->city;
            $result->country = $k->country;
            return $result;
        });
    }
}

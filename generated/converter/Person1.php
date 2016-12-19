<?php
namespace Generated\Converter;

use QzPhp\Linq;

class Person1{
    public function __construct() {
        $this->phones = new \QzPhp\AutoMapper\ValueConverter("array", "phone");
        $this->educations = new \Generated\Converter\Person1_educations();
        
    }


    public function convert($data, $additional) {
        return Linq::select($data, function($k) use($additional){
            $result = new \Models\Person();
            $result->name = $k->name;
            $result->birth = $k->birth;
            $result->id = $k->identitication_no;
            $result->phones = $this->phones->convert($additional["phones"]);
            $result->educations = $this->educations->convert($additional["educations"]);
            return $result;
        });
    }
}

<?php
namespace QzPhp;

class BoolToYesNo{
    public function __construct($context = []){
        $this->context = array_merge(["language" => "en"], $context);
        $this->language = [
            "en" => [true => "yes", false => "no"],
            "id" => [true => "ya", false => "tidak"],
            "de" => [true => "ja", false => "nein"]
        ];
    }

    private $context = NULL;
    private $language = [];
    public function get($value, $option = []){
        $option = array_merge([
                "case" => "lower"
            ], $option);
        if($option["case"] == "upper"){
            return strtoupper($this->language[$this->context['language']][$value]);
        }
        else{
            return $this->language[$this->context['language']][$value];   
        }
    }
}

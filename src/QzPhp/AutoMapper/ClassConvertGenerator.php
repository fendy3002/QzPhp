<?php

namespace QzPhp\AutoMapper;
use QzPhp\Q;
use QzPhp\Linq;

class ClassConvertGenerator
{
    public function __construct($className, $fields){
        $this->className = $className;
        $this->fields = $fields;
    }
    public $className;
    public $fields;

    public function generate(){
        $fields = $this->fields;
        $generatedClassName = str_replace('\\', '_', $this->className);

        $generator = new \QzPhp\SingleMethodClassGenerator($generatedClassName);

        $generator->_namespace = 'QzPhp\AutoMapper\Generated';
        $generator->_methodName = 'convert';
        $generator->_parameters = [
            '$data, $additional'
        ];
        $generator->_use = [
            'QzPhp\Linq',
            'QzPhp\AutoMapper'
        ];

        $conversion = "";
        foreach($fields as $key => $value){
            if(is_object($value)){
                if($value->type == "array"){
                    if(!empty($value->value)){

                    }
                    if(!empty($value->schema)){

                    }
                }
                else if($value->type == "object"){

                }
            }
            else{
                $value = $value ?: $key;
                $conversion .= '$result->'.$key.' = $k->'.$value.';' . "\n";
            }
        }

        $generator->_methodBody = 'return Linq::select($data, function($k){
            $result = new \\'. $this->className .'();'.
            $conversion .
        '   return $result;
        });';
        $generator->generate();
    }
}

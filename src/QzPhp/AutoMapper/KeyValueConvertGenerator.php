<?php

namespace QzPhp\AutoMapper;
use QzPhp\Q;
use QzPhp\Linq;

class KeyValueConvertGenerator
{
    public function __construct($className, $type, $fields){
        $this->className = $className;
        $this->type = $type;
        $this->fields = $fields;
    }
    public $type;
    public $fields;
    public $className;

    public function generate(){
        $fields = $this->fields;
        $generator = new \QzPhp\SingleMethodClassGenerator($this->className);

        $generator->_namespace = 'QzPhp\AutoMapper\Generated';
        $generator->_methodName = 'convert';
        $generator->_parameters = [
            '$data'
        ];
        $generator->_use = [
            'QzPhp\Linq',
            'QzPhp\AutoMapper'
        ];

        $conversion = "";
        foreach($fields as $key => $value){
            $value = $value ?: $key;
            $conversion .= '$result->'.$key.' = $k->'.$value.';' . "\n";
        }

        $generator->_methodBody = 'return Linq::select($data, function($k){
            $result = (object)[];'.
            $conversion .
        '   return $result;
        });';

        $generator->generate();
    }
}

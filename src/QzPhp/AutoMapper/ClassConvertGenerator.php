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
        $generatedClassName = $this->generateClassName($this->className);

        $generator = new \QzPhp\SingleMethodClassGenerator($generatedClassName);

        $generator->_namespace = 'QzPhp\AutoMapper\Generated';
        $generator->_methodName = 'convert';
        $generator->_parameters = [
            '$data, $additional'
        ];
        $generator->_use = [
            'QzPhp\Linq'
        ];

        $conversion = "";
        foreach($fields as $key => $value){
            if(is_object($value)){
                if($value->type == "array"){
                    if(!empty($value->value)){
                        $conversion .= $this->generateValue($generator, $key, $value);
                    }
                    else if(!empty($value->fields)){

                    }
                    else if(!empty($value->schema)){

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

        $generator->_methodBody = 'return Linq::select($data, function($k) use($additional){
            $result = new \\'. $this->className .'();'.
            $conversion .
        '   return $result;
        });';
        $generator->generate();
    }

    private function generateClassName($className){
        return str_replace('\\', '_', $this->className);
    }

    private function generateValue($generator, $key, $value){
        $generator->properties[] = 'private $' . $key . ';';
        $generator->_constructorBody .=
            '$this->' . $key . " = new \QzPhp\AutoMapper\ValueConverter(".
            '"' . $value->type . '", ' .
            '"' . $value->value . '"' .
            ");\n";
        return '$result->'. $key . ' = $this->' . $key . '->convert($additional["'.$key.'"]);';
    }

    private function generateKeyValue($generator, $key, $value){
        $generator->properties[] = 'private $' . $key . ';';

        $generatedClassName = $this->generateClassName($this->className) . '_' . $key;
        $keyValueConvertGenerator = new KeyValueConvertGenerator(
            $generatedClassName,
            $value->type
        );
        $generator->_constructorBody .=
            '$this->' . $key . " = new " . $generatedClassName . ";\n";
    }
}

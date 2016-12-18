<?php

namespace QzPhp\AutoMapper;
use QzPhp\Q;
use QzPhp\Linq;

class ClassConvertGenerator
{
    public function __construct($schema){
        $this->schema = $schema;
    }
    public $schema;

    public function generate(){
        $result = [];
        foreach($this->schema as $schemaName => $schema){
            $className = $schema->className;
            $fields = $schema->fields;

            $generator = new \QzPhp\ClassGenerator($schemaName);

            $generator->setNamespace('QzPhp\AutoMapper\Generated')
                ->setImports([
                    'QzPhp\Linq'
                ]);

            $conversion = "";
            foreach($fields as $key => $value){
                if(is_object($value)){
                    if($value->type == "array"){
                        if(!empty($value->value)){
                            $conversion .= $this->generateValue($generator, $key, $value) . "\n";
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
                    $conversion .= '    $result->'.$key.' = $k->'.$value.';' . "\n";
                }
            }

            $generator->addMethod('convert',
                'return Linq::select($data, function($k) use($additional){'. "\n".
                '    $result = new \\'. $schema->className .'();'. "\n".
                    $conversion .
                '    return $result;' . "\n".
                '});',
                ['$data, $additional']
            );

            $result[$schemaName] = $generator->generateClassDefinition();
        }

        return $result;
    }

    private function generateValue($generator, $key, $value){
        $generator->properties[] = 'private $' . $key . ';';
        $generator->_constructorBody .=
            '$this->' . $key . " = new \QzPhp\AutoMapper\ValueConverter(".
            '"' . $value->type . '", ' .
            '"' . $value->value . '"' .
            ");\n";
        return '    $result->'. $key . ' = $this->' . $key . '->convert($additional["'.$key.'"]);';
    }

    private function generateKeyValue($generator, $key, $value){
        $generator->properties[] = 'private $' . $key . ';';

        $generatedClassName = $this->generateClassName($this->className) . '_' . $key;
        $keyValueConvertGenerator = new KeyValueConvertGenerator(
            $generatedClassName,
            $value->type
        );
        $generator->_constructorBody .=
            '    $this->' . $key . " = new " . $generatedClassName . ";\n";
    }
}

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

            $schemaNamespace = substr($schemaName, 0, strrpos($schemaName, "\\"));
            $schemaClassName = substr($schemaName, strrpos($schemaName, "\\") + 1);
            $generator = new \QzPhp\ClassGenerator($schemaClassName);

            $generator->setNamespace($schemaNamespace)
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
                            $keyValue = $this->generateKeyValue($generator, $schemaNamespace, $schemaClassName, $key, $value);
                            $conversion .= $keyValue->statement . "\n";
                            $filePath = Q::Z()->io()->combine($schema->folder, $keyValue->className . ".php");
                            $result[$keyValue->className] = (object)[
                                "filePath" => $filePath,
                                "definition" => $keyValue->definition,
                                'schemaName' => $schemaNamespace . "\\" . $keyValue->className
                            ];
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

            $filePath = Q::Z()->io()->combine($schema->folder, $schemaClassName . ".php");
            $result[$schemaName] = (object)[
                "filePath" => $filePath,
                "definition" => $generator->generateClassDefinition(),
                'schemaName' => $schemaName
            ];
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

    private function generateKeyValue($generator, $nameSpace, $className, $key, $value){
        $generator->properties[] = 'private $' . $key . ';';

        $generatedClassName = $className . '_' . $key;
        $keyValueConvertGenerator = new KeyValueConvertGenerator(
            $nameSpace,
            $generatedClassName,
            $value->type,
            $value->fields
        );
        $generator->_constructorBody .=
            '$this->' . $key . " = new \\" . $nameSpace . "\\" . $generatedClassName . "();\n";
        return (object)[
            'className' => $generatedClassName,
            'definition' => $keyValueConvertGenerator->generate(),
            'statement' => '    $result->'. $key . ' = $this->' . $key . '->convert($additional["'.$key.'"]);'
        ];
    }
}

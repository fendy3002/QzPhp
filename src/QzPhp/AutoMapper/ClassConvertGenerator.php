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
                            $conversion .= $this->generateSchema($generator, $key, $value) . "\n";
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
                ['$data', '$additional = []']
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

        $methodBody = $this->generateArray(
            $key,
            $value,
            '$result->'. $key . ' = $this->' . $key . '->convert($mapped);'
        );
        return $methodBody;
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

        $methodBody = $this->generateArray(
            $key,
            $value,
            '$result->'. $key . ' = $this->' . $key . '->convert($mapped);'
        );
        return (object)[
            'className' => $generatedClassName,
            'definition' => $keyValueConvertGenerator->generate(),
            'statement' => $methodBody
        ];
    }

    private function generateSchema($generator, $key, $value){
        $generator->_constructorBody .=
            '$this->' . $key . " = new \\{$value->schema}();\n";
        $additionalVar = '$additional["' . $key . '_additional"]';
        $statement = '';
        $statement .= '$_additional = !empty(' . $additionalVar . ') ? ' . $additionalVar . ' : [];' . "\n";
        $statement .= '$result->'. $key . ' = $this->' . $key . '->convert($mapped, $_additional);';

        $methodBody = $this->generateArray(
            $key,
            $value,
            $statement
        );
        return $methodBody;
    }

    private function generateArray($key, $value, $statement){
        $t = "    ";
        $methodBody = '';
        $methodBody .= '    if(!empty($additional["' . $key . '"]) && count($additional["' . $key . '"]) > 0){' . "\n";
        if(!empty($value->key)){
            $conversions = [];
            foreach($value->key as $source => $converted){
                $conversions[] = $t . $t . $t . $t . '$result->' . $source . ' == $n->' . $converted;
            }

            $methodBody .= $t . $t .'$mapped = Linq::where($additional["' . $key . '"], function($n) use($result) {'. "\n";
            $methodBody .= $t . $t . $t . 'return '. "\n";
            $methodBody .= implode(" && " . "\n", $conversions);

            $methodBody .= ';'. "\n";
            $methodBody .= $t . $t . '});'. "\n";
        }
        else{
            $methodBody .= '$mapped = $additional["' . $key . '"];'. "\n";
        }
        $statement = implode("\n",
            Linq::select(explode("\n", $statement), function($k){
                return '        ' . $k;
            })
        );
        $methodBody .= $statement . "\n";
        $methodBody .= '    }';
        return $methodBody;
    }
}

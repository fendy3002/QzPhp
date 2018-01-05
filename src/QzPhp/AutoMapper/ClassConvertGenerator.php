<?php

namespace QzPhp\AutoMapper;
use QzPhp\Q;
use QzPhp\Linq;

class ClassConvertGenerator
{
    public function __construct($config, $schemaList = []){
        $this->config = $config;
        $this->schemaList = $schemaList;
    }
    public $config;
    public $schemaList;

    public function generate($schemaDefinition){
        $result = [];
        foreach($schemaDefinition as $schemaName => $schema){
            if($schemaName == "Version"){ continue; }
            $className = $schema->className;
            $fields = $schema->fields;

            $definedSchema = (object)[
                "folder" => "",
                "fields" => (object)[]
            ];
            if(!empty($this->schemaList[$className])){
                $definedSchema = $this->schemaList[$className];
            }

            $dateFormat = $this->config['dateFormat'];
            $dateTimeFormat = $this->config['dateTimeFormat'];

            $schemaNamespace = substr($schemaName, 0, strrpos($schemaName, "\\"));
            $schemaClassName = substr($schemaName, strrpos($schemaName, "\\") + 1);
            $generator = new \QzPhp\ClassGenerator($schemaClassName);
            $generator->setExtends("\QzPhp\AutoMapper\BaseMapper");
            $generator->_constructorBody .= "parent::__construct([" . "\n".
            "    'dateFormat' => '$dateFormat'," . "\n".
            "    'dateTimeFormat' => '$dateTimeFormat'" . "\n".
            "]);" . "\n";

            $generator->setNamespace($schemaNamespace)
                ->setImports([
                    'QzPhp\Linq'
                ]);

            $conversion = "";
            foreach($fields as $key => $value){
                if(is_object($value)){
                    if(!empty($value->value)){
                        $conversion .= $this->generateValue($generator, $key, $value) . "\n";
                        $generator->addProperty("public $" . $key . ";");
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

                        $generator->addProperty("public $" . $key . ";");
                    }
                    else if(!empty($value->schema)){
                        $conversion .= $this->generateSchema($generator, $key, $value) . "\n";
                        $generator->addProperty('public $_' . $key . ";");
                    }
                    else if(!empty($value->ref) && $value->ref == "true"){
                        $conversion .= $this->generateRef($generator, $key, $value) . "\n";
                    }
                }
                else{
                    $value = $value ?: $key;
                    if(strtolower($value) != "::null"){
                        if(!empty($definedSchema->fields->$key)){
                            $field = $definedSchema->fields->$key;

                            if($field == "date"){
                                $conversion .= '    $result->' . $key . ' = $this->date($k->' . $value . ');' . "\n";
                            }
                            else if($field == "datetime"){
                                $conversion .= '    $result->' . $key . ' = $this->datetime($k->' . $value . ');' . "\n";
                            }
                            else if($field == "int"){
                                $conversion .= '    $result->' . $key . ' = $this->int($k->' . $value . ');' . "\n";
                            }
                            else if($field == "bool" || $field == "boolean"){
                                $conversion .= '    $result->' . $key . ' = $this->bool($k->' . $value . ');' . "\n";
                            }
                            else{
                                $conversion .= '    $result->' . $key . ' = $k->' . $value . ';' . "\n";
                            }
                        }
                        else{
                            $conversion .= '    $result->' . $key . ' = $k->' . $value . ';' . "\n";
                        }
                    }
                }
            }
            $generator->addMethod('convertOne',
                '$results = $this->convert([$data], $additional);' . "\n" .
                '$result = $results[0];' . "\n" .
                'return $result;',
                ['$data', '$additional = []']
            );

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

        $statement = '';
        if($value->type == "array"){
            $statement .= '$result->'. $key . ' = $this->' . $key . '->convert($mapped);';
        }
        else if($value->type == "object"){
            $statement .= 'if(count($mapped) > 0){' . "\n";
            $statement .= '    $valueMaps = $this->' . $key . '->convert($mapped);' . "\n";
            $statement .= '    $result->'. $key . ' = $valueMaps[0];' . "\n";
            $statement .= '}' . "\n";
            $statement .= 'else{' . "\n";
            $statement .= '    $result->'. $key . ' = NULL;' . "\n";
            $statement .= '}';
        }

        $methodBody = $this->generateArray(
            $key,
            $value,
            $statement
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

        $statement = '';
        if($value->type == "array"){
            $statement .= '$result->'. $key . ' = $this->' . $key . '->convert($mapped);';
        }
        else if($value->type == "object"){
            $statement .= 'if(count($mapped) > 0){' . "\n";
            $statement .= '    $valueMaps = $this->' . $key . '->convert($mapped);' . "\n";
            $statement .= '    $result->'. $key . ' = $valueMaps[0];' . "\n";
            $statement .= '}' . "\n";
            $statement .= 'else{' . "\n";
            $statement .= '    $result->'. $key . ' = NULL;' . "\n";
            $statement .= '}';
        }
        $methodBody = $this->generateArray(
            $key,
            $value,
            $statement
        );
        return (object)[
            'className' => $generatedClassName,
            'definition' => $keyValueConvertGenerator->generate(),
            'statement' => $methodBody
        ];
    }

    private function generateSchema($generator, $key, $value){
        $generator->addMethod(
            $key . '_',
            '$this->_' . $key . ' = $this->_' . $key . " ?: new \\{$value->schema}();\n" .
            'return $this->_' . $key . ';',
            []);

        $additionalVar = '$additional["' . $key . '_additional"]';
        $statement = '';
        $statement .= '$_additional = !empty(' . $additionalVar . ') ? ' . $additionalVar . ' : [];' . "\n";
        if($value->type == "array"){
            $statement .= '$result->'. $key . ' = $this->' . $key . '_()->convert($mapped, $_additional);';
        }
        else if($value->type == "object"){
            $statement .= 'if(count($mapped) > 0){' . "\n";
            $statement .= '    $result->'. $key . ' = $this->' . $key . '_()->convertOne($mapped[0], $_additional);' . "\n";
            $statement .= '}' . "\n";
            $statement .= 'else{' . "\n";
            $statement .= '    $result->'. $key . ' = NULL;' . "\n";
            $statement .= '}';
        }

        $methodBody = $this->generateArray(
            $key,
            $value,
            $statement
        );
        return $methodBody;
    }
    private function generateRef($generator, $key, $value){
        $statement = '';
        if($value->type == "array"){
            $statement .= '$result->'. $key . ' = $mapped;';
        }
        else if($value->type == "object"){
            $statement .= 'if(count($mapped) > 0){' . "\n";
            $statement .= '    $result->'. $key . ' = $mapped[0];' . "\n";
            $statement .= '}' . "\n";
            $statement .= 'else{' . "\n";
            $statement .= '    $result->'. $key . ' = NULL;' . "\n";
            $statement .= '}';
        }

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
        $methodBody .= '    if(!empty($additional["' . $key . '"])){' . "\n";
        if(!empty($value->key)){
            $conversions = [];
            foreach($value->key as $source => $converted){
                $conversions[] = $t . $t . $t . $t . '$k->' . $source . ' == $n->' . $converted;
            }

            $methodBody .= $t . $t .'$mapped = Linq::where($additional["' . $key . '"], function($n) use($k) {'. "\n";
            $methodBody .= $t . $t . $t . 'return '. "\n";
            $methodBody .= implode(" && " . "\n", $conversions);

            $methodBody .= ';'. "\n";
            $methodBody .= $t . $t . '});'. "\n";
        }
        else if(!empty($value->link)){
            $linkdata = '$additional["' . $key . '_link"]';
            $conversions = [];
            foreach($value->link->from as $source => $converted){
                $conversions[] = $t . $t . $t . $t . '$k->' . $source . ' == $m->' . $converted;
            }
            foreach($value->link->with as $source => $converted){
                $conversions[] = $t . $t . $t . $t . '$m->' . $source . ' == $n->' . $converted;
            }

            $methodBody .= $t . $t .'$mapped = Linq::whereExistsIn($additional["' . $key . '"], ' . $linkdata . ', function($n, $m) use($k) {'. "\n";
            $methodBody .= $t . $t . $t . 'return '. "\n";
            $methodBody .= implode(" && " . "\n", $conversions);

            $methodBody .= ';'. "\n";
            $methodBody .= $t . $t . '});'. "\n";
        }
        else{
            $methodBody .= $t . $t . '$mapped = $additional["' . $key . '"];'. "\n";
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

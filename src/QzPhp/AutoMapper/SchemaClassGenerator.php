<?php

namespace QzPhp\AutoMapper;
use QzPhp\Q;
use QzPhp\Linq;

class SchemaClassGenerator
{
    public function __construct($config){
        $this->config = $config;
    }
    public $config;

    public function generate($schema){
        $result = [];
        foreach($schema as $schemaName => $schema){
            if($schemaName == "Version"){ continue; }
            $fields = $schema->fields;

            $nameSpace = substr($schemaName, 0, strrpos($schemaName, "\\"));
            $className = substr($schemaName, strrpos($schemaName, "\\") + 1);
            $generator = new \QzPhp\ClassGenerator($className);

            $generator->setNamespace($nameSpace)
                ->setImports([
                    'QzPhp\Linq'
                ]);

            $conversion = "";
            foreach($fields as $fieldName => $type){
                $defaultValue = substr($type, strlen($type) -2) == "[]" ? " = []" : "";
                $doc =
                    "/**" . "\n" .
                    " * @var " . $type . "\n" .
                    " */";
                $property = 'public $' . $fieldName . $defaultValue . ";";
                $generator->addProperty($property, $doc);
            }

            $filePath = Q::Z()->io()->combine($schema->folder, $className . ".php");
            $result[$schemaName] = (object)[
                "filePath" => $filePath,
                "raw" => $schema,
                "definition" => $generator->generateClassDefinition(),
                'schemaName' => $schemaName
            ];
        }

        return $result;
    }
}

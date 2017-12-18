<?php

namespace QzPhp\AutoMapper;
use QzPhp\Q;
use QzPhp\Linq;

class SchemaClassGenerator
{
    public function __construct($schema){
        $this->schema = $schema;
    }
    public $schema;

    public function generate(){
        $result = [];
        foreach($this->schema as $schemaName => $schema){
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
                $doc =
                    "/**" . "\n" .
                    " * @var " . $type . "\n" .
                    " */";
                $property = 'public $' . $fieldName . ";";
                $generator->addProperty($property, $doc);
            }

            $filePath = Q::Z()->io()->combine($schema->folder, $className . ".php");
            $result[$schemaName] = (object)[
                "filePath" => $filePath,
                "definition" => $generator->generateClassDefinition(),
                'schemaName' => $schemaName
            ];
        }

        return $result;
    }
}

<?php

namespace QzPhp\AutoMapper;
use QzPhp\Q;
use QzPhp\Linq;

class KeyValueConvertGenerator
{
    public function __construct($nameSpace, $className, $type, $fields){
        $this->nameSpace = $nameSpace;
        $this->className = $className;
        $this->type = $type;
        $this->fields = $fields;
    }
    public $nameSpace;
    public $type;
    public $fields;
    public $className;

    public function generate(){
        $fields = $this->fields;
        $generator = new \QzPhp\ClassGenerator($this->className);

        $generator->setNamespace($this->nameSpace)
            ->setImports([
                'QzPhp\Linq',
                'QzPhp\AutoMapper'
            ]);

        $conversion = "";
        foreach($fields as $key => $value){
            $value = $value ?: $key;
            $conversion .= '    $result->'.$key.' = $k->'.$value.';' . "\n";
        }

        $generator->addMethod('convert',
            'return Linq::select($data, function($k){' . "\n" .
            '    $result = (object)[];'. "\n" .
                $conversion . "\n" .
            '    return $result;'. "\n" .
            '});',
            ['$data']
        );

        return $generator->generateClassDefinition();
    }
}

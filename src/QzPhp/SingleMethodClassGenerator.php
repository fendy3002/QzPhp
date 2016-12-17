<?php

namespace QzPhp;
use QzPhp\Linq;

class SingleMethodClassGenerator
{
    public function __construct($name){
        $this->_name = $name;
    }

    private $_name = '';
    public $_namespace = 'Generated';
    public $_methodName = 'execute';
    public $_parameters = [];
    public $_use = [];
    public $_methodBody = '';

    public $_properties = [];
    public $_constructorParameters = [];
    public $_constructorBody = '';

    public function generate(){
        $classDefinition = $this->generateClassDefinition();
        eval($classDefinition);
    }

    public function generateClassDefinition(){
        $uses = Linq::select($this->_use, function($k){
            return 'use ' . $k . ';';
        });
        $use = implode("\n", $uses);
        $parameter = implode(',', $this->_parameters);
        $constructorParameter = implode(',', $this->_constructorParameters);
        $property = implode("\n", $this->_properties);

        $classDefinition =
            "namespace {$this->_namespace};\n" .
            "$use\n" .
            "class {$this->_name}{\n".
            "   public function __construct($constructorParameter) {\n".
                "$this->_constructorBody". "\n" .
            "   }\n".
            "   $property\n".
            "   public function {$this->_methodName}($parameter) {\n".
                "$this->_methodBody". "\n" .
            "   }\n".
            "}";
        return $classDefinition;
    }
}

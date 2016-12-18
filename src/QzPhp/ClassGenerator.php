<?php

namespace QzPhp;
use QzPhp\Linq;

class ClassGenerator
{
    public function __construct($name){
        $this->_name = $name;
    }

    private $_name = '';
    private $_namespace = 'Generated';
    public function getNamespace(){
        return $this->_namespace;
    }
    public function setNamespace($namespace){
        $this->_namespace = $namespace;
        return $this;
    }

    private $_methods = [];
    public function getMethods(){
        return $this->_methods;
    }
    public function setMethods(array $methods){
        $this->_methods = $methods;
        return $this;
    }
    public function addMethod($methodName, $methodBody, array $parameters = []){
        $this->_methods[] = (object)[
            'methodName' => $methodName,
            'parameters' => $parameters,
            'methodBody' => $methodBody
        ];
        return $this;
    }

    private $_use = [];
    public function getImports(){
        return $this->_use;
    }
    public function setImports(array $use){
        $this->_use = $use;
        return $this;
    }
    public function addImport($use){
        $this->_use[] = $use;
        return $this;
    }

    private $_properties = [];
    public function getProperties(){
        return $this->_properties;
    }
    public function setProperties(array $properties){
        $this->_properties = $properties;
        return $this;
    }
    public function addProperty($property){
        $this->_properties[] = $property;
        return $this;
    }

    public $_constructorParameters = [];
    public $_constructorBody = '';
    public function getContructor(){
        return (object)[
            'parameters' => $this->_constructorParameters,
            'body' => $this->_constructorBody
        ];
    }
    public function setContructor($body, array $parameters = []){
        $this->_constructorBody = $body;
        $this->_constructorParameters = $parameters;
    }

    public function generate(){
        $classDefinition = $this->generateClassDefinition();
        eval($classDefinition);
    }

    public function generateClassDefinition(){
        $t1 = "    "; $t2 = $t1 + $t1; $t3 = $t2 + $t1; $t4 = $t2 + $t2;
        $uses = Linq::select($this->_use, function($k){
            return 'use ' . $k . ';';
        });
        $use = implode("\n", $uses);
        $constructorParameter = implode(',', $this->_constructorParameters);
        $property = implode("\n", $this->_properties);

        $methods = Linq::select($this->_methods, function($k) use($t1){
            $parameter = implode(', ', $k->parameters);
            return $t1 . "public function {$k->methodName}($parameter) {\n".
                "$k->methodBody". "\n" .
            $t1 . "}\n";
        });
        $method = implode("\n", $methods);

        $classDefinition =
            "namespace {$this->_namespace};\n" .
            "$use\n" .
            "class {$this->_name}{\n".
            $t1 . "public function __construct($constructorParameter) {\n".
                "$this->_constructorBody". "\n" .
            $t1 . "}\n".
            "$property\n".
            "$method\n".
            "}";
        return $classDefinition;
    }
}

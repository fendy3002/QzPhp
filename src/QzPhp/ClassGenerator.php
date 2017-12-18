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
        $result = [];
        foreach($properties as $property){
            if(is_object($property)){
                $result[] = $property;
            }
            else if(is_array($property)){
                $result[] = (object)[
                    'value' => $property['value'],
                    'documentation' => $property['documentation']
                ];
            }
            else{
                $result[] = (object)[
                    'value' => $property,
                    'documentation' => NULL
                ];
            }
        }
        $this->_properties = $result;
        return $this;
    }
    public function addProperty($property, $documentation = NULL){
        $this->_properties[] = (object)[
            'value' => $property,
            'documentation' => $documentation
        ];
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

    public $_extends;
    public function getExtends($extends){
        return $this->_extends;
    }
    public function setExtends($extends){
        $this->_extends = $extends;
    }

    public function generate(){
        $classDefinition = $this->generateClassDefinition();
        eval($classDefinition);
    }

    public function generateClassDefinition(){
        $t1 = "    "; $t2 = $t1 . $t1; $t3 = $t2 . $t1; $t4 = $t2 . $t2;
        $uses = Linq::select($this->_use, function($k){
            return 'use ' . $k . ';';
        });
        $use = implode("\n", $uses);
        $constructorParameter = implode(',', $this->_constructorParameters);

        $properties = Linq::select($this->_properties, function($k) use($t1){
            $documentation = !empty($k->documentation) ? $k->documentation . "\n" : "";
            $documentations = Linq::select(explode("\n", $documentation), function($l) use ($t1){
                return $t1 . $l;
            });
            $documentation = implode("\n", $documentations);
            return $documentation .
                $k->value;
        });
        $property = implode("\n", $properties);

        $methods = Linq::select($this->_methods, function($k) use($t1){
            $parameter = implode(', ', $k->parameters);
            $methodBodies = Linq::select(explode("\n", $k->methodBody), function($l) use ($t1){
                return $t1 . $t1 . $l;
            });
            $methodBody = implode("\n", $methodBodies);
            return $t1 . "public function {$k->methodName}($parameter) {\n".
                "$methodBody". "\n" .
            $t1 . "}\n";
        });
        $method = implode("\n", $methods);
        $constructorBodies = Linq::select(explode("\n", $this->_constructorBody), function($k) use ($t1){
            return $t1 . $t1 . $k;
        });
        $constructorBody = implode("\n", $constructorBodies);
        $extends = "";
        if($this->_extends){ $extends = " extends " . $this->_extends; }

        $classDefinition =
            "namespace {$this->_namespace};\n" .
            "\n".
            "$use\n" .
            "\n".
            "class {$this->_name} $extends {\n".
            $t1 . "public function __construct($constructorParameter) {\n".
                "$constructorBody". "\n" .
            $t1 . "}\n".
            "$property\n".
            "\n".
            "$method".
            "}\n";
        return $classDefinition;
    }
}

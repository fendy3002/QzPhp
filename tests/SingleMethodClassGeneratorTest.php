<?php
namespace Test;

class SingleMethodClassGeneratorTest extends \Tests\TestCase
{
    public function testGenerate()
    {
        $generator = new \QzPhp\SingleMethodClassGenerator("Example");

        $generator->_namespace = 'Generated';
        $generator->_methodName = 'execute';
        $generator->_parameters = [
            '$name',
            '$address'
        ];
        $generator->_use = [
            'QzPhp\Q'
        ];

        $generator->_properties = [
            'public $name = "Luke ";',
            'public $address;',
        ];
        $generator->_methodBody = "return
            [
                'text' => \$this->name . \$name . '-' . \$address,
                'uuid' => Q::Z()->uuid()
            ];";

        $generator->generate();
        $newClass = new \Generated\Example();
        $name = 'name1';
        $address = 'address1';
        $result = $newClass->execute($name, $address);

        $expectedText = 'Luke ' . $name . '-' . $address;
        $this->assertEquals($expectedText, $result['text']);
    }
}

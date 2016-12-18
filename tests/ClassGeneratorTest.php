<?php
namespace Test;

class ClassGeneratorTest extends \Tests\TestCase
{
    public function testGenerate()
    {
        $generator = new \QzPhp\ClassGenerator("Example");

        $generator->setNamespace('Generated')
            ->setImports([
                'QzPhp\Q'
            ])->setProperties([
                'public $name = "Luke ";',
                'public $address;'
            ])->addMethod(
                'execute',
                "return [
                    'text' => \$this->name . \$name . '-' . \$address,
                    'uuid' => Q::Z()->uuid()
                ];",
                [
                    '$name',
                    '$address'
                ]
            );

        $generator->generate();
        $newClass = new \Generated\Example();
        $name = 'name1';
        $address = 'address1';
        $result = $newClass->execute($name, $address);

        $expectedText = 'Luke ' . $name . '-' . $address;
        $this->assertEquals($expectedText, $result['text']);
    }
}

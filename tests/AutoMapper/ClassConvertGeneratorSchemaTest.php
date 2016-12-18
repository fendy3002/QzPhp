<?php
namespace Tests\AutoMapper;

class ClassConvertGeneratorSchemaTest extends \Tests\TestCase
{
    public function test()
    {
        $schemaRaw = file_get_contents(__DIR__ . '../../../storage/test/MapSchema/Converter.json');
        $schema = json_decode($schemaRaw);
        print_r($schema);

        $generator = new \QzPhp\AutoMapper\ClassConvertGenerator($schema);
        $result = $generator->generate();
        file_put_contents(__DIR__ . '/generated.txt', $result);
    }
}

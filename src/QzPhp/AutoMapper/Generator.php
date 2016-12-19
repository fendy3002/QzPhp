<?php

namespace QzPhp\AutoMapper;
use QzPhp\Q;

class Generator
{
    public function __construct($rootPath, $config, $log = NULL){
        $this->rootPath = $rootPath;
        $this->config = $config;
        $this->log = $log ?: new \QzPhp\Logs\ConsoleLog();
    }
    public $rootPath;
    public $config;

    public function generate(){
        $schemaFolder = Q::Z()->io()->combine($this->rootPath, $this->config['schema']);
        $converterFolder = Q::Z()->io()->combine($this->rootPath, $this->config['converter']);

        $this->log->messageLn("START:\t generate converter from schema in folder: $converterFolder");
        foreach(scandir($converterFolder) as $file){
            if($file == '.' || $file == '..'){
                continue;
            }
            else{
                $schema = file_get_contents(Q::Z()->io()->combine($converterFolder, $file));
                $schema = json_decode($schema);

                $classGenerator = new \QzPhp\AutoMapper\ClassConvertGenerator($schema);
                $classDefinitions = $classGenerator->generate();
                foreach($classDefinitions as $definition){
                    $this->log->messageLn("CREATE:\t converter {$definition->schemaName}");
                    $writeTo = Q::Z()->io()->combine(
                        $this->rootPath,
                        $this->config['destinationPath'],
                        $definition->filePath);
                    $content = "<?php\n" .
                        $definition->definition;
                    Q::Z()->io()->write($writeTo, $content);
                    $this->log->messageLn("DONE:\t converter {$definition->schemaName} created");
                }
            }
        }
        $this->log->messageLn("DONE:\t generate converter done");
    }
}

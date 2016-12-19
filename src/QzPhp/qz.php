<?php

$red = "\033[0;31m";
$brown = "\033[0;33m";
$green = "\033[0;32m";
$white = "\033[0m";
$root = getcwd();
require $root.'/vendor/autoload.php';

if(count($argv) == 1){
    echo "QzPHP Tools\n";
    echo "===========\n";
    echo "{$brown}Usage:{$white}\n";
    echo "  command [options] [arguments]";

    echo "\n";
    echo "\n";
    echo "{$brown}Available commands:{$white}\n";
    echo "{$green}  mapper\t{$white}generate mapper classes\n";
}
else{
    $action = $argv[1];

    if($action == "mapper"){
        $options = array_slice($argv, 2);
        foreach($options as $option){
            if($option == "-h"){
                echo "QzPHP Tools\n";
                echo "===========\n";

                echo "\n";
                echo "Generate mapper class\n";
                echo "\n";
                echo "Available options:\n";
                exit;
            }
        }

        $configPath = $root . '/qz.config.php';
        if(file_exists($configPath)){
            $config = include($configPath);
            $mapConfig = $config['mapper'];

            $generator = new \QzPhp\AutoMapper\Generator($root, $mapConfig);
            $generator->generate();
        }
        else{
            echo "{$red}Error at generate mapper class:{$white}\n";
            echo "qz.config.php not found in current folder\n";
        }
    }
}

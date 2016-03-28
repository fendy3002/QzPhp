<?php
namespace QzPhp\Logs;

class ConsoleLog implements ILog {
    public function message($message, $params = NULL){
        printf($message, $params);
    }
    public function messageln($message = NULL, $params = NULL){
        printf($message . "\n", $params);
    }
    public function object ($object){
        var_dump($object);
    }
    public function exception($ex){
        var_dump($ex);
    }
}

<?php
namespace QzPhp\Logs;

class ConsoleLog implements ILog {
    public function message($message){
        echo $message;
    }
    public function messageln($message = NULL){
        echo $message . "\n";
    }
    public function object($object){
        var_dump($object);
    }
    public function exception($ex){
        var_dump($ex);
    }
}

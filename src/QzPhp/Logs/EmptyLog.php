<?php
namespace QzPhp\Logs;

class EmptyLog implements ILog {
    public function message($message, $params = NULL){
    }
    public function messageln($message = NULL, $params = NULL){
    }
    public function object ($object){
    }
    public function exception($ex){
    }
    private function write($message){
    }
}

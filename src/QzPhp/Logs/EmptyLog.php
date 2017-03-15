<?php
namespace QzPhp\Logs;

class EmptyLog implements ILog {
    public function message($message){
    }
    public function messageln($message = NULL){
    }
    public function object ($object){
    }
    public function exception($ex){
    }
    private function write($message){
    }
}

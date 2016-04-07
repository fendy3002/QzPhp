<?php
namespace QzPhp;

class Log {
    public function __construct($log = null){
        if(!empty($log)){
            $this->log = $log;
        }else{
            $this->log = new \QzPhp\Logs\ConsoleLog();
        }
    }
    public $log;

    public function message($message, $params = NULL){
        $this->log->message($message, $params);
    }
    public function messageln($message = NULL, $params = NULL){
        $this->log->messageln($message, $params);
    }
    public function object($object){
        $this->log->object($object);
    }
    public function exception($ex){
        $this->log->exception($ex);
    }
}

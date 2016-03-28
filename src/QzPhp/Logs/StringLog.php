<?php
namespace QzPhp\Logs;

class StringLog implements ILog {
    public $data = '';
    public function message($message, $params = NULL){
        $this->data .= $this->data . sprintf($message, $params);
    }
    public function messageln($message = NULL, $params = NULL){
        $this->data .= $this->data . sprintf($message. "\n", $params);
    }
    public function object ($object){
        $this->data .= print_r($object, true);
    }
}

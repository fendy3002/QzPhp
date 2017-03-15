<?php
namespace QzPhp\Logs;

class StringLog implements ILog {
    public $data = '';
    public function message($message){
        $this->data .= $this->data . $message;
    }
    public function messageln($message = NULL){
        $this->data .= $this->data . $message. "\n";
    }
    public function object ($object){
        $this->data .= print_r($object, true);
    }
}

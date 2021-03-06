<?php
namespace QzPhp\Logs;

class PrefixTimeLog implements ILog {
    public function __construct(ILog $inner, $prefix = '', $format = 'c'){
        $this->inner = $inner;
        $this->prefix = $prefix;
        $this->format = $format;
    }
    private $inner;
    public $prefix = '';
    private $format = '';
    public function message($message){
        $this->inner->message($this->getPrefix() . ' ' . $message);
    }
    public function messageln($message = NULL){
        $this->inner->messageln($this->getPrefix() . ' ' . $message);
    }
    public function object ($object){
        $this->inner->messageln($this->getPrefix());
        $this->inner->object($object);
    }
    public function exception ($object){
        $this->inner->messageln($this->getPrefix());
        $this->inner->exception($object);
    }

    private function getPrefix(){
        return $this->prefix . ' ' . $this->getDate();
    }
    private function getDate(){
        return gmdate($this->format);
    }
}

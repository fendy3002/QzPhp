<?php
namespace QzPhp\Logs;

class FileLog implements ILog {
    public function __construct($filePath){
        $this->filePath = $filePath;
    }
    private $filePath = '';
    public $dateFormat = 'Y-m-d H:i:s';

    public function message($message){
        $add = $message;
        $this->write($add);
    }
    public function messageln($message = NULL){
        $add = $message. "\n";
        $this->write($add);
    }
    public function object ($object){
        $add = print_r($object, true);
        $this->write($add);
    }
    public function exception($ex){
        $add = gmdate($this->dateFormat) . ' ' . $ex->getMessage() . ' ' . $ex->getTraceAsString() . "\n";
        $this->write($add);
    }

    private function write($message){
        $myfile = fopen($this->filePath, "a");
        fwrite($myfile, $message);
        fclose($myfile);
    }
}

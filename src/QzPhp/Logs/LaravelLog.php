<?php
namespace QzPhp\Logs;
use Log;
class LaravelLog implements ILog {

    public function message($message){
        $add = $message;
        Log::debug($add);
    }
    public function messageln($message = NULL){
        $add = $message. "\n";
        Log::debug($add);
    }
    public function object ($object){
        $add = print_r($object, true);
        Log::debug($add);
    }

    public function exception($ex){
        $add = print_r($ex, true);
        Log::error($add);
    }
}

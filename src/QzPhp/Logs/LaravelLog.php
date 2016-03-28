<?php
namespace QzPhp\Logs;
use Log;
class LaravelLog implements ILog {

    public function message($message, $params = NULL){
        $add = sprintf($message, $params);
        Log::debug($add);
    }
    public function messageln($message = NULL, $params = NULL){
        $add = sprintf($message. "\n", $params);
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

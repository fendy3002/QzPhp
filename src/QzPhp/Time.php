<?php
namespace QzPhp;
use Carbon\Carbon;

class Time{
    public function between($nowStr, $fromStr, $toStr, $format = 'G.i'){
        $from = Carbon::createFromFormat($format, $fromStr);
        $to = Carbon::createFromFormat($format, $toStr);
        $now = Carbon::createFromFormat($format, $nowStr);

        if($from->gt($to)){
            if($now->gte($from) || $now->lte($to)){
                return true;
            }
            else{
                return false;
            }
        }
        else{
            if($now->gte($from) && $now->lte($to)){
                return true;
            }
            else{
                return false;
            }
        }
    }
}

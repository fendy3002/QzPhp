<?php
namespace QzPhp;

class Q{
    private static $instance;
    public static function Z(){
        if ( empty( self::$instance ) )
        {
          self::$instance = new Q();
        }
        return self::$instance;
    }

    public function string(){
        return new String();
    }
    public function io(){
        return new IO();
    }

    public function url(){
        return new Url();
    }
    public function curl($url){
        return new CUrl($url);
    }

    public function time(){
        return new Time();
    }

    public function stringEmpty($str){
        return (empty($str) || trim($str)==='');
    }

    public function coalesceString(){
        foreach (func_get_args() as $param) {
            if(!empty($param) && !$this->stringEmpty($param)){
                return $param;
            }
        }
        return NULL;
    }

    public function ifNull($source, $replacement){
        if(empty($source)){ return $replacement; }
        return $source;
    }
    public function uuid($data = null){
        return Uuid::getRaw($data);
    }
    public function hypotenuse($a, $b){
        return Math::hypotenuse($a, $b);
    }
    public function unionArray(){
        return Linq::union();
    }

    public function result($attr){
        $result = new \QzPhp\Models\Result();
        if(!empty($attr['messages'])){
            $result->setMessage($attr['messages']);
        }
        else if(!empty($attr['message'])){
            $result->addMessage($attr['message']);
        }

        if(array_key_exists('success', $attr)){
            $result->setSuccess($attr['success']);
        }
        if(!empty($attr['stackTrace'])){
            $result->setStackTrace($attr['stackTrace']);
        }
        if(!empty($attr['data'])){
            $result->setData($attr['data']);
        }

        return $result;
    }

    public function arrayIntersect($key, $append){
        $result = array_merge($key, []);
        foreach($key as $k=>$l){
            if(!empty($append[$k])){
                $result[$k] = $append[$k];
            }
        }
        return $result;
    }

	public static function enum($data){
		return new Enum($data);
	}

    public static function db($dbConf, $logObj = NULL){
        $dbGen = new \QzPhp\DBGenerator();
        $dbh = $dbGen->get($dbConf['host'], $dbConf['user'],
            $dbConf['password'], $dbConf['database']);
        return new \QzPhp\QDB($dbh, $logObj);
    }

    public function assign($obj, $attr){
        foreach($attr as $key=>$value){
            if(property_exists($obj, $key)){
                $obj->$key = $value;
            }
            else{
                throw new \Exception("Property " . $key . " not exists in class: " . get_class($obj));
            }
        }
        return $obj;
    }
}

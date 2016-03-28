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
}

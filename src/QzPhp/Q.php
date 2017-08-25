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
    public function __construct(){
        $this->mock = [];
    }
    private $mock;
    public function addMock($module, $mock){
        $this->mock[$module] = $mock;
    }
    public function addMocks($mock){
        $this->mock = array_merge($this->mock, $mock);
    }
    public function clearMock(){
        $this->mock = [];
    }
    public function arr($arr){
        return $this->mock['arr'] ?: new Arr($arr);
    }
    public function string(){
        return $this->mock['string'] ?: new Str();
    }
    public function io(){
        return $this->mock['io'] ?: new IO();
    }
    public function geo(){
        return $this->mock['geo'] ?: new Geo();
    }

    public function url(){
        return $this->mock['url'] ?: new Url();
    }
    public function curl($url){
        return $this->mock['curl'] ?: new CUrl($url);
    }

    public function time(){
        return $this->mock['time'] ?: new Time();
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

	public function enum($data){
		return $this->mock['enum'] ?: new Enum($data);
	}

    public function db($dbConf, $logObj = NULL){
        $dbGen = new \QzPhp\DBGenerator();
        $dbh = $dbGen->get($dbConf['host'], $dbConf['user'],
            $dbConf['password'], $dbConf['database']);
        return new \QzPhp\QDB($dbh, $logObj);
    }
    public function mySqlDb($dbConf, $logObj = NULL){
        $dbGen = new \QzPhp\DBGenerator();
        $dbh = $dbGen->get($dbConf['host'], $dbConf['user'],
            $dbConf['password'], $dbConf['database']);
        return new \QzPhp\MySqlQDB($dbh, $logObj);
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

    public function boolToYesNo($context){
        return $this->mock['boolToYesNo'] ?: new BoolToYesNo($context);
    }
}

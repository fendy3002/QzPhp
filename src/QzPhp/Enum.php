<?php
namespace QzPhp;

class Enum {
	public function __construct($data){
		$this->data = $data;
		$this->commands = array();
	}
	public $data;
	protected $commands;

	public function where($handler){
		array_push($this->commands, function($data) use($handler){
			return Linq::where($data, $handler);
		});
		return $this;
	}

	public function whereNotIn($compared, $comparer){
		array_push($this->commands, function($data) use($compared, $comparer){
			return Linq::whereNotIn($data, $compared, $comparer);
		});
		return $this;
	}
	public function select($handler){
		array_push($this->commands, function($data) use($handler){
			return Linq::select($data, $handler);
		});
		return $this;
	}
	public function selectKeyValue($handler){
		array_push($this->commands, function($data) use($handler){
			return Linq::selectKeyValue($data, $handler);
		});
		return $this;
	}

	public function orderBy($handler){
		array_push($this->commands, function($data) use($handler){
			return Linq::orderBy($data, $handler);
		});
		return $this;
	}

	public function groupBy($select, $compare = NULL){
		array_push($this->commands, function($data) use($select, $compare){
			return Linq::groupBy($data, $select, $compare);
		});
		return $this;
	}

	public function join($with, $select, $compare){
		array_push($this->commands, function($data) use($with, $select, $compare){
			return Linq::join($data, $with, $select, $compare);
		});
		return $this;
	}

	public function distinct($handler = NULL){
		array_push($this->commands, function($data) use($handler){
			return Linq::distinct($data, $handler);
		});
		return $this;
	}

	public function union(){
		$args = func_get_args();
		array_push($this->commands, function($data) use($args){
			$result = $data;
	        foreach ($args as $param) {
	            $result = array_merge($result, $param);
	        }
			return $result;
		});
		return $this;
	}

	public function extend($additional){
		array_push($this->commands, function($data) use($additional){
			return Linq::extend($data, $additional);
		});
		return $this;
	}

	public function any($handler){
		$result = $this->value();
		return Linq::any($result, $handler);
	}

	public function mergeString($handler){
		$result = $this->value();
		return Linq::mergeString($result, $handler);
	}

	public function flat(){
		array_push($this->commands, function($data) {
			return Linq::flat($data);
		});
		return $this;
	}

	public function toKeyValue($key, $value){
		array_push($this->commands, function($data) use($key, $value){
			return Linq::toKeyValue($data, $key, $value);
		});
		return $this;
	}

	public function firstOrDefault($handler, $default){
		$result = $this->value();
		return Linq::firstOrDefault($result, $handler, $default);
	}

	public function firstOrNull($handler = NULL){
		$handler = $handler ?: function($k){
			return true;
		};
		$result = $this->value();
		return Linq::firstOrNull($result, $handler);
	}

	public function sum($handler){
		$result = $this->value();
		return Linq::sum($result, $handler);
	}

	public function max($handler){
		$result = $this->value();
		return Linq::max($result, $handler);
	}

	public function min($handler){
		$result = $this->value();
		return Linq::min($result, $handler);
	}

	public function contains($key){
		$result = $this->value();
		return Linq::contains($result, $key);
	}

	public function value(){
		$result = array_merge(array(), $this->data); //prevent reference keep
		if(count($this->commands) > 0){
			foreach($this->commands as $command){
				$result = $command($result);
			}
		}
		unset($this->commands);
		$this->commands = array();
		return $result;
	}

	public function val(){
		return $this->value();
	}

	public function result(){
		return $this->value();
	}
}

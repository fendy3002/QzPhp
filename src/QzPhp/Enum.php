<?php
namespace QzPhp;

class Enum {
	public function __construct($data){
		$this->data = $data;
		$this->commands = array();
	}
	public $data;
	protected $commands;

    public function __clone() {
        $this->commands = array_merge([], $this->commands);
    }

	public function where($handler){
		$toReturn = clone $this;
		array_push($toReturn->commands, function($data) use($handler){
			return Linq::where($data, $handler);
		});
		return $toReturn;
	}

	public function whereNotIn($compared, $comparer){
		$toReturn = clone $this;
		array_push($toReturn->commands, function($data) use($compared, $comparer){
			return Linq::whereNotIn($data, $compared, $comparer);
		});
		return $toReturn;
	}
	
	public function whereExistsIn($compared, $comparer){
		$toReturn = clone $this;
		array_push($toReturn->commands, function($data) use($compared, $comparer){
			return Linq::whereExistsIn($data, $compared, $comparer);
		});
		return $toReturn;
	}
	
	public function select($handler){
		$toReturn = clone $this;
		array_push($toReturn->commands, function($data) use($handler){
			return Linq::select($data, $handler);
		});
		return $toReturn;
	}
	public function selectKeyValue($handler){
		$toReturn = clone $this;
		array_push($toReturn->commands, function($data) use($handler){
			return Linq::selectKeyValue($data, $handler);
		});
		return $toReturn;
	}

	public function orderBy($handler){
		$toReturn = clone $this;
		array_push($toReturn->commands, function($data) use($handler){
			return Linq::orderBy($data, $handler);
		});
		return $toReturn;
	}

	public function groupBy($select, $compare = NULL){
		$toReturn = clone $this;
		array_push($toReturn->commands, function($data) use($select, $compare){
			return Linq::groupBy($data, $select, $compare);
		});
		return $toReturn;
	}

	public function join($with, $select, $compare){
		$toReturn = clone $this;
		array_push($toReturn->commands, function($data) use($with, $select, $compare){
			return Linq::join($data, $with, $select, $compare);
		});
		return $toReturn;
	}

	public function distinct($handler = NULL){
		$toReturn = clone $this;
		array_push($toReturn->commands, function($data) use($handler){
			return Linq::distinct($data, $handler);
		});
		return $toReturn;
	}

	public function union(){
		$toReturn = clone $this;
		$args = func_get_args();
		array_push($toReturn->commands, function($data) use($args){
			$result = $data;
	        foreach ($args as $param) {
	            $result = array_merge($result, $param);
	        }
			return $result;
		});
		return $toReturn;
	}

	public function extend($additional){
		$toReturn = clone $this;
		array_push($toReturn->commands, function($data) use($additional){
			return Linq::extend($data, $additional);
		});
		return $toReturn;
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
		$toReturn = clone $this;
		array_push($toReturn->commands, function($data) {
			return Linq::flat($data);
		});
		return $toReturn;
	}

	public function toKeyValue($key, $value){
		$toReturn = clone $this;
		array_push($toReturn->commands, function($data) use($key, $value){
			return Linq::toKeyValue($data, $key, $value);
		});
		return $toReturn;
	}

	public function toKeyArray($key, $value = NULL){
		$toReturn = clone $this;
		array_push($toReturn->commands, function($data) use($key, $value){
			return Linq::toKeyArray($data, $key, $value);
		});
		return $toReturn;
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

	public function getByMax($handler){
		$result = $this->value();
		return Linq::getByMax($result, $handler);
	}
	public function getByMin($handler){
		$result = $this->value();
		return Linq::getByMin($result, $handler);
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
		//prevent reference keep
		$result = array_merge(array(), $this->data);
		if(count($this->commands) > 0){
			foreach($this->commands as $command){
				$result = $command($result);
			}
		}
		return $result;
	}

	public function val(){
		return $this->value();
	}

	public function result(){
		return $this->value();
	}
}

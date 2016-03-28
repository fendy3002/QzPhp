<?php
namespace QzPhp;

class Linq {
	public static function where($data, $handler){
		$result = array();
		for($i = 0; $i < count($data); $i++){
			if($handler($data[$i])){
				array_push($result, $data[$i]);
			}
		}
		return $result;
	}

	public static function select($data, $handler){
		$result =  array();
		for($i = 0; $i < count($data); $i++){
			$items = $handler($data[$i]);
			if($items != null){
				array_push($result, $items);
			}
		}
		return $result;
	}

	// return a new array grouped by parameter
	public static function groupBy($data, $groupedBy, $comparer = NULL){
		if(count($data) == 0){
			return $data;
		}
		$comparer = Q::Z()->ifNull($comparer, function($a, $b){ return $a == $b; });

		$result = array();
		for($i = 0; $i < count($data); $i++){
			$grouped = $groupedBy($data[$i]);
			$isExists = false;
			foreach($result as $existing){
				if($comparer($grouped, $existing->key))
				{
					array_push($existing->value, $data[$i]);
					$isExists = true;
					break;
				}
			}
			if(!$isExists){
				$added = (object)["key" => $grouped, "value" => array($data[$i])];
				array_push($result, $added);
			}
		}

		return $result;
	}

	// return a new array with joined data
	public static function join($a, $b, $select, $comparer = NULL){
		if(count($a) == 0){
			return $a;
		}
		if(count($b) == 0){
			return $b;
		}
		$comparer = Q::Z()->ifNull($comparer, function($a, $b){ return $a == $b; });

		$result = array();
		foreach($a as $eachA){
			foreach($b as $eachB){
				if($comparer($eachA, $eachB)){
					array_push($result, $select($eachA, $eachB));
				}
			}
		}

		return $result;
	}

	public static function union(){
        $result = array();
        foreach (func_get_args() as $param) {
            $result = array_merge($result, $param);
        }
        return $result;
	}

	public static function firstOrNull($data, $handler){
		return Linq::firstOrDefault($data, $handler, NULL);
	}

	public static function firstOrDefault($data, $handler, $default = NULL){
		for($i = 0; $i < count($data); $i++){
			if($handler($data[$i])){
				return $data[$i];
			}
		}
		if(func_num_args() > 2){
			func_get_arg(2);
		}
		else{
			return $default;
		}
	}

	public static function any($data, $handler){
		if(!empty($handler)){
			for($i = 0; $i < count($data); $i++){
				if($handler($data[$i])){
					return true;
				}
			}
			return false;
		}
		else{
			return count($data) > 0;
		}
	}

	public static function orderBy($data, $handler){
		if(count($data) <= 1){
			return $data;
		}
		$result = array_values($data);
		usort($result, $handler);
		return $result;
	}

	public static function sum($data, $handler){
		$result =  0;
		for($i = 0; $i < count($data); $i++){
			$num = (float)$handler($data[$i]);
			$result += $num;
		}
		return $result;
	}

	public static function max($data, $handler){
		if(count($data) == 0){
			return null;
		}
		else if(count($data) == 1){
			return (float)$handler($data[0]);
		}
		$result = (float)$handler($data[0]);

		for($i = 1; $i < count($data); $i++){
			$num = (float)$handler($data[$i]);;
			$result = $result < $num ? $num : $result;
		}
		return $result;
	}

	public static function min($data, $handler){
		if(count($data) == 0){
			return null;
		}
		else if(count($data) == 1){
			return (float)$handler($data[0]);;
		}
		$result = (float)$handler($data[0]);;

		for($i = 1; $i < count($data); $i++){
			$num = (float)$handler($data[$i]);;
			$result = $result > $num ? $num : $result;
		}
		return $result;
	}

	public static function distinct($data, $handler = NULL){
		if(count($data) == 1){
			return $data;
		}
		$handler = Q::Z()->ifNull($handler, function($k,$l){return $k==$l;});
		$result = array();
		for($i = 0; $i < count($data); $i++){
			$exists = false;
			for($j = 0; $j < count($result); $j++){
				$exists = $handler($data[$i], $result[$j]);
				if($exists){ break; }
			}
			if(!$exists){
				array_push($result, $data[$i]);
			}
		}
		return $result;
	}

	// return a new array that has been aggregated by handler
	public static function aggregate($data, $handler){
		$result = NULL;
		if(count($data) == 0){
			return $data;
		}
		else if(count($data) == 1){
			return $data[0];
		}
		else{
			for($i = 0; $i < count($data) - 1; $i++){
				if($i == 0){
					$result = $handler($data[$i], $data[$i+1]);
				}
				else{
					$result = $handler($result, $data[$i+1]);
				}
			}
		}
		return $result;
	}

	// return a flatten array from array of arrays
	public static function flat($data){
		$result = NULL;
		if(count($data) == 0){
			return $data;
		}
		else if(count($data) == 1){
			return $data[0];
		}
		else{
			$result = [];
			for($i = 0; $i < count($data); $i++){
				$result = array_merge($result, $data[$i]);
			}
		}
		return $result;
	}

	// return a new string that has been merged by handler
	public static function mergeString($data, $handler){
		$result = NULL;
		if(count($data) == 0){
			return NULL;
		}
		else if(count($data) == 1){
			return $handler($data[0]);
		}
		else{
			for($i = 0; $i < count($data); $i++){
				$result .= $handler($data[$i]);
			}
		}
		return $result;
	}

	// return a new array which has same fields as source,
	// then override and append from additional
	public static function extend($source, $additional){
		$result = new stdClass();

		foreach(array_keys((array)$source) as $key){
			$result->$key = $source->$key;
		}
		foreach(array_keys((array)$additional) as $key){
			$result->$key = $additional->$key;
		}
		return $result;
	}

	// return a new array which has same fields as source,
	// and modified by additional value if any
	public static function appendLeft($source, $additional){
		$result = new stdClass();

		foreach(array_keys((array)$source) as $key){
			if(isset($additional->$key)){
				$result->$key = $additional->$key;
			}
			else{
				$result->$key = $source->$key;
			}
		}
		return $result;
	}

	public static function whereExistsIn($source, $compared, $key){
		return Linq::where($source, function($k) use($compared, $key){
            return Linq::any($compared, function($l) use ($k, $key){
				return $key($k, $l);
			});
        });
	}

	public static function contains($source, $key){
		return	in_array($key, $source);
	}

	public static function enum($data){
		return new Enum($data);
	}
}

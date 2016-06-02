<?php
namespace QzPhp;

class QDB{
    public function __construct($dbh, $logObj = NULL){
        $this->dbh = $dbh;
        $this->logObj = $logObj;
    }
    function __destruct(){
        unset($this->dbh);
        unset($this->logObj);
    }

    public $dbh;
    public $logObj;
    public function log($obj){
        if(empty($this->logObj)){
            $logObj = function($obj){
                var_dump($obj);
            };
            $this->logObj = $logObj;
        }
        $logObj = $this->logObj;
        $logObj($obj);
    }

    public function beginTransaction(){
        return $this->dbh->beginTransaction();
    }

    public function commit(){
        return $this->dbh->commit();
    }

    public function statement($query, $params){
        $stmt = $this->dbh->prepare($query);
        foreach($params as $key => $value){
            $stmt->bindParam(':' . $key, $$key);
            $$key = $value;
        }
        $stmt->execute();
        if($stmt->errorCode() != '00000'){
            $this->log($stmt->errorInfo());
        }
    }

    public function insert($table, $list){
        if(count($list) == 0){
            return;
        }
        $insert_values = array();
        $query = 'INSERT INTO ' . $table .'(';
        $queryValues = ' VALUES ';
        $queryValue = '(';
        $isFirst = true;
        $firstData = $list[0];
        ksort($firstData);
        foreach($firstData as $key => $value){
            if(!$isFirst){
                $query .= ', ';
                $queryValue .= ', ';
            }
            $query .= '`' . $key . '`';
            $queryValue .= '?';
            $isFirst = false;
        }
        $query .= ') ';
        $queryValue .= ')';

        $isFirst = true;
        foreach($list as $d){
            if(!$isFirst){
                $queryValues .= ', ';
            }
            $queryValues .= $queryValue;
            ksort($d);
            $insert_values = array_merge($insert_values, array_values($d));
            $isFirst = false;
        }
        $query .= $queryValues;
        $stmt = $this->dbh->prepare($query);
        $stmt->execute($insert_values);
        if($stmt->errorCode() != '00000'){
            $this->log($stmt->errorInfo());
        }
    }

    public function update($table, $data, $fields, $where){
        $fieldClause = '';
        $isFirst = true;
        foreach($fields as $key=>$value){
            if(!$isFirst){
                $fieldClause .= ', ';
            }
            $fieldClause .= 'a.' . $key . ' = :' . $key . ' ';
            $isFirst = false;
        }
        $isFirst = true;
        $whereClause = '';
        foreach ($where as $key => $value) {
            if(!$isFirst){
                $whereClause .= ' and ';
            }
            $whereClause .= 'a.' . $key . ' = :' . $key . ' ';
            $isFirst = false;
        }
        $query = 'update ' . $table . ' a set ' . $fieldClause . ' where ' . $whereClause;

        $prep = $this->dbh->prepare($query);
        foreach($data as $each){
            $param = [];
            foreach($fields as $key=>$value){
                $param[':' . $key] = $each[$value];
            }
            foreach($where as $key=>$value){
                $param[':' . $key] = $each[$value];
            }

            $prep->execute($param);
            if($prep->errorCode() != '00000'){
                $this->log($prep->errorInfo());
            }
        }
    }

    public function select($table, $fields){
        $prep = $this->dbh->prepare($query);
        foreach($params as $key => $value){
            $stmt->bindParam(':' . $key, $$key);
            $$key = $value;
        }
        $stmt->execute();
        if($stmt->errorCode() != '00000'){
            $this->log($stmt->errorInfo());
        }
        else{
            $result = $stmt->fetchAll();
            return \QzPhp\Q::Z()->enum($result)
                ->select(function($k){
                    return (object)$k;
                })->result;
        }
        return $result;
    }
}

<?php
namespace QzPhp;

class MySqlQDB extends QDB{
    public function __construct($dbh, $logObj = NULL){
        parent::__construct($dbh, $logObj);
    }
    function __destruct(){
        parent::__destruct();
    }

    public function insertIgnore($table, $list){
        if(count($list) == 0){
            return;
        }
        $insert_values = array();
        $query = 'INSERT IGNORE INTO ' . $table .'(';
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
}

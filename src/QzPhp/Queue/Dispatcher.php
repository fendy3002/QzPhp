<?php
namespace QzPhp\Queue;
use Carbon\Carbon;

/**
 * Helper class to dispatch QzNode's queue job
 */
class Dispatcher{
    public function __construct($config){
        $connection = [
            "host" => "localhost", 
            "user" => "root", 
            "password" => null, 
            "database" => 'db',
            "port" => 3306
        ];
        if($config['connection']){
            $connection = array_merge(
                $connection, 
                $config['connection']
            );
        }
        $usedConfig = array_merge([
            "tableName" => "qz_queue",
            "timezone" => "UTC"
        ], $config);
        $usedConfig['connection'] = $connection;
        $this->config = $usedConfig;
    }
    public function dispatch($scriptPath, $param = NULL, $options = NULL){
        $usedOption = array_merge([
            "tag" => "default",
            "priority" => 3,
            "when" => null
        ], $options);

        $runAt = null;
        if(empty($usedOption['when'])){
            $runAt = gmdate("Y-m-d\TH:i:s");
        }
        else if(strtolower($this->config['timezone']) != "utc" && 
            strtolower($this->config['timezone']) != "etc\utc"){
            $carbonDate = null;
            if($usedOption['when'] instanceof \DateTime){
                $carbonDate = Carbon::instance($usedOption['when']);
            }
            else{
                $carbonDate = new Carbon($usedOption["when"], $this->config['timezone']);
            }
            $carbonDate->setTimezone('UTC');
            $runAt = $carbonDate->format("Y-m-d\TH:i:s");
        }
        $dbGen = new \QzPhp\DBGenerator();
        $db = new \QzPhp\QDB($dbGen->get(
            $this->config['connection']['host'], 
            $this->config['connection']['user'], 
            $this->config['connection']['password'], 
            $this->config['connection']['database'], 
            $this->config['connection']['port']
        ));

        $insertParam = empty($param) ? "{}" : json_encode($param);

        $db->insert($this->config['tableName'], [
            [
                'tag' => $usedOption['tag'],
                'utc_run' => $runAt,
                'run_script' => $scriptPath,
                'params' => $insertParam,
                'priority' => $usedOption['priority'],
                'retry' => 0,
                'utc_created' => gmdate("Y-m-d\TH:i:s")
            ]
        ]);
    }
}
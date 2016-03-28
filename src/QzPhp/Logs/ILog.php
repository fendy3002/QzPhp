<?php
namespace QzPhp\Logs;

interface ILog {
    public function message($message, $params = NULL);
    public function messageln($message = NULL, $params = NULL);
    public function object ($object);
    public function exception ($ex);
}

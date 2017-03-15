<?php
namespace QzPhp\Logs;

interface ILog {
    public function message($message);
    public function messageln($message = NULL);
    public function object ($object);
    public function exception ($ex);
}

<?php

namespace QzPhp\Connection;
use Session;

class FtpConnection
{
    public function __construct($setting){
        $this->server = $setting->server;
        if(!empty($setting->user)){
            $this->user = $setting->user;
        }
        if(!empty($setting->password)){
            $this->password = $setting->password;
        }
        if(!empty($setting->port)){
            $this->port = $setting->port;
        }else{
            $this->port = 0;
        }
        if(!empty($setting->timeout)){
            $this->timeout = $setting->timeout;
        }
        else{
            $this->timeout = 300;
        }
    }
    public $server;
    public $user;
    public $password;
    public $port;
    public $timeout;

    /**
     * Send file through ftp connection
     * @param  string $localFile source file
     * @param  string $toFile    destination file name
     * @return QzPhp\Models\Result
     */
    public function send($localFile, $toFile = NULL){
        $conn = ftp_connect ($this->server, $this->port, $this->timeout);
        $login_result = ftp_login($conn, $this->user, $this->password);
        $toFile = !empty($toFile) ? $toFile : basename($localFile);

        $uploadResult = ftp_put($conn, $toFile, $localFile, FTP_ASCII);
        ftp_close($conn);

        return \QzPhp\Q::Z()->result([
            'success' => $uploadResult,
            'message' => 'sent to ftp path: ' . $toFile,
            'data' => $toFile
        ]);
    }
}

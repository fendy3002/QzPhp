<?php
namespace QzPhp;

class IO{
    public function combine(){
        $args = func_get_args();
        if(empty($args) || count($args) == 0){
            return null;
        }
        if(count($args) == 1){
            return $args[0];
        }

        $result = rtrim($args[0], DIRECTORY_SEPARATOR);
        for($i = 1; $i < count($args); $i++){
            $result .= DIRECTORY_SEPARATOR . rtrim($args[$i], DIRECTORY_SEPARATOR);
        }
        return $result;
    }

    public function getFolderSize($path){
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            return $this->getFolderSizeWindows($path);
        } else {
            return $this->getFolderSizeLinux($path);
        }
    }
    private function getFolderSizeWindows($path){
        $path = realpath($path);
        if($path!==false){
            $bytestotal = 0;
            foreach(new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path,
                \FilesystemIterator::SKIP_DOTS)) as $object){
                try{
                    $bytestotal += $object->getSize();
                }catch(\Exception $ex){

                }
            }
            return $bytestotal;
        }
        else{
            return null;
        }
    }
    private function getFolderSizeLinux($path){
        $io = popen ( '/usr/bin/du -sk ' . $path, 'r' );
        $size = fgets ( $io, 4096);
        $size = substr ( $size, 0, strpos ( $size, "\t" ) );
        pclose ( $io );
        return $size;
    }
}

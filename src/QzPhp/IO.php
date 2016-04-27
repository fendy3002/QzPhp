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

    public function zip($source, $saveAs){
        // Get real path for our folder
        $rootPath = realpath($source);

        // Initialize archive object
        $zip = new \ZipArchive();
        $zip->open($saveAs, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

        // Create recursive directory iterator
        /** @var SplFileInfo[] $files */
        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($rootPath),
            \RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($files as $name => $file)
        {
            // Skip directories (they would be added automatically)
            if (!$file->isDir())
            {
                // Get real and relative path for current file
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen($rootPath) + 1);

                // Add current file to archive
                $zip->addFile($filePath, $relativePath);
            }
        }

        // Zip archive will be created only after closing object
        $zip->close();
    }

    public function unzip($zipFile, $extractTo = NULL){
        $extractTo = empty($extractTo) || $extractTo == '' ? $this->directoryOf($zipFile) : $extractTo;

        $zip = new \ZipArchive;
        $res = $zip->open($zipFile);
        if ($res === TRUE) {
            $zip->extractTo($extractTo);
            $zip->close();
        } else {
            throw new \Exception('file not found');
        }
    }

    public function directoryOf($file){
        $path_parts = pathinfo($file);
        return $path_parts['dirname'];
    }

    public function deleteFolderContent($path){
        $path = rtrim($path, DIRECTORY_SEPARATOR);
        $files = glob($path . DIRECTORY_SEPARATOR . '*' ); // get all file names
        foreach($files as $file){ // iterate files
            if(is_dir($file)) {
                $this->deleteFolderContent($file);
                rmdir($file);
            } else {
                unlink($file);
            }
        }
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

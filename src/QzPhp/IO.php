<?php
namespace QzPhp;

class IO{
    /**
     * Combine physical path
     * @var string[] Paths to combiine (unlimited args)
     * @return string Combined path
     */
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

    /**
     * Same with file_put_contents, but create folder beforehand if not exists
     * @param  string  $filePath Physical file path
     * @param  string  $content  Content to write
     * @param  integer $option  file_put_contents options
     * @return void
     */
    public function write($filePath, $content, $option = NULL){
        $dir = $this->directoryOf($filePath);
        if(!file_exists($dir)){
            mkdir($dir, 0777, true);
        }

        if(empty($option)){
            file_put_contents($filePath, $content);
        }
        else{
            file_put_contents($filePath, $content, $option);
        }
    }

    /**
     * Zip files
     * @param  string[] $source List of file names to zip
     * @param  string $saveAs Filepath to write
     * @return void
     */
    public function zip($source, $saveAs){
        $sources = [];
        if(!is_array($source)){
            $sources = [$source];
        }
        else{
            $sources = $source;
        }

        $files = [];
        foreach($sources as $each){
            // Get real path for our folder
            $rootPath = realpath($each);

            if(is_dir($rootPath)){
                // Create recursive directory iterator
                /** @var SplFileInfo[] $files */
                $recursive = new \RecursiveIteratorIterator(
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
                        $files[] = (object)["filePath" => $filePath, "saveAs" => $relativePath];
                    }
                }
            }
            else{
                $files[] = (object)["filePath" => $rootPath, "saveAs" => basename($rootPath)];
            }
        }

        // Initialize archive object
        $zip = new \ZipArchive();
        $zip->open($saveAs, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

        foreach ($files as $file)
        {
            $zip->addFile($file->filePath, $file->saveAs);
        }

        // Zip archive will be created only after closing object
        $zip->close();
    }

    /**
     * Extract zip file
     * @param  string $zipFile   path to zip file
     * @param  string $extractTo path of extracted files
     * @return void
     */
    public function unzip($zipFile, $extractTo = NULL){
        if(empty($extractTo) || $extractTo == ''){
            $path_parts = pathinfo($file);
            $extractTo = $path_parts['dirname'] . $path_parts['filename'];
        }

        $zip = new \ZipArchive;
        $res = $zip->open($zipFile);
        if ($res === TRUE) {
            $zip->extractTo($extractTo);
            $zip->close();
        } else {
            throw new \Exception('file not found');
        }
    }

    /**
     * Get directory of file
     * @param  string   $file   file path
     * @return string           directory path of file
     */
    public function directoryOf($file){
        $path_parts = pathinfo($file);
        return $path_parts['dirname'];
    }

    /**
     * Delete all content in folder
     * @param  string $path folder path
     * @return void
     */
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

    /**
     * read file by part and process each part. Separated by newline
     * @param  string  $file    file path
     * @param  closure  $process function to be processed
     * @param  integer $chunk   chunk size, default 32 kb or 2 ^ 15
     * @return void
     */
    public function readFileByPart($file, $process, $chunk = 32768, $delimiter = "\n"){
        $fileReader = new FileReader();
        $fileReader->readFileByPart($file, $process, $chunk, $delimiter);
    }

    public function basename($path){
        $result = basename($path);
        if(strpos($result, '?') !== FALSE){
            return substr($result, 0, strpos($result, '?'));
        }
        else{
            return $result;
        }
    }

    public function fileExists($filename){
        return file_exists($filename);
    }
    public function touch($filename){
        return touch($filename);
    }
    public function deleteFile($filename){
        return unlink($filename);
    }

    public function readFile($filename){
        return file_get_contents($filename);
    }
    public function writeFile($filename, $data, $flags = 0){
        return file_put_contents($filename, $data, $flags);
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

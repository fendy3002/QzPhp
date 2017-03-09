<?php
namespace QzPhp;

class FileReader{
    /**
     * read file by part and process each part. Separated by newline
     * @param  string  $file    file path
     * @param  closure  $process function to be processed
     * @param  integer $chunk   chunk size, default 32 kb or 2 ^ 15
     * @return void
     */
    public function readFileByPart($file, $process, $chunk = 32768, $delimiter = "\n"){
        $fseekPos = 0;
        $handle = fopen($file, "r");
        while(!feof($handle)){
            fseek($handle, $fseekPos);
            if(($content = fread($handle, $chunk)) !== false){
                $contentLength = strrpos($content, $delimiter);
                $content = substr($content, 0, $contentLength);
                $process($content);
                $fseekPos = $fseekPos + $contentLength + 1;
            }
        }

        fclose($handle);
    }

    public function readFileByLines($filepath, $startPos, $limit){
        $fp = @fopen($filepath, "r");
        $line = 0;
        $pos = $startPos;
        $message = '';
        $fileSize = filesize($filepath);
        while($line < $limit && $pos <= $fileSize) {
            if(fseek($fp, $pos, SEEK_SET) == 0){
                $t = fgetc($fp);
                $pos = $pos + 1;

                if($t == "\n"){ $line++; }
            }
            else{ //EOF
                fseek($fp, $pos - 1, SEEK_SET);
                break;
            }
        }
        $length = 0;
        if($line == $limit){ // ignore the last newline
            $length = $pos - $startPos - 1;
        }
        else{
            $length = $pos - $startPos;
        }
        fseek($fp, $startPos, SEEK_SET);
        return (object)[
            'pos' => $pos,
            "content" => fread($fp, $length)
        ];
    }

    public function readFileByLinesR($filepath, $startPos, $limit){
        $fp = @fopen($filepath, "r");
        $line = 0;
        $pos = -$startPos;
        $message = '';
        while($line < $limit) {
            if(fseek($fp, $pos, SEEK_END) == 0){
                $t = fgetc($fp);
                $pos = $pos - 1;

                if($t == "\n"){ $line++; }
            }
            else{ //EOF
                fseek($fp, $pos + 1, SEEK_END);
                break;
            }
        }
        $length = 0;
        if($line == $limit){ // ignore last newline
            fseek($fp, $pos + 2, SEEK_END);
        }
        else{
            fseek($fp, $pos, SEEK_END);
        }
        $length = ((-$pos) - $startPos);

        return (object)[
            'pos' => (-$pos) + 1,
            "content" => fread($fp, $length)
        ];
    }
}

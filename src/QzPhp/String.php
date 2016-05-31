<?php
namespace QzPhp;

class String{
    public function startsWith($haystack, $needle){
        $length = strlen($needle);
        return (substr($haystack, 0, $length) === $needle);
    }
    public function endsWith($haystack, $needle){
        $length = strlen($needle);
        if ($length == 0) {
            return true;
        }

        return (substr($haystack, -$length) === $needle);
    }
    public function replaceMany($source, $matches){
        $patterns = [];
        $replacements = [];
        foreach($matches as $key=>$value){
            $patterns[] = $key;
            $replacements[] = $value;
        }
        return str_replace($patterns, $replacements, $source);
    }
}

<?php

namespace App\Helpers;

use Setting;

class Format
{
    public static function Bytes($size){
        
        if ($size > 0) {
            $size = (int) $size;
            $base = log($size) / log(1024);
            $suffixes = explode(",", Setting::get('format_bytes'));
            
            return round(pow(1024, $base - floor($base)), Setting::get('format_precision')) . $suffixes[floor($base)];
        } else {
            return $size;
        }
        
    }
}
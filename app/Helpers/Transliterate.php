<?php

namespace App\Helpers;

use Setting;

class Transliterate
{
    public static function get($string) {
        
        if(Setting::get('use_transliterate') != 'yes')
            return $string;
        
        $map    = self::map();
        $string = str_replace(array_keys($map), array_values($map), $string);
        
        return $string;
        
    }
    
    public static function getMap() {
        return self::map();
    }
    
    private static function map() {
        
        return [
            'ж' => 'zh', 'ч' => 'ch', 'щ' => 'shh', 'ш' => 'sh',
            'ю' => 'yu', 'ё' => 'yo', 'я' => 'ya', 'э' => 'e',
            'а' => 'a', 'б' => 'b', 'в' => 'v',
            'г' => 'g', 'д' => 'd', 'е' => 'e', 'з' => 'z',
            'и' => 'i', 'й' => 'y', 'к' => 'k', 'л' => 'l',
            'м' => 'm', 'н' => 'n', 'о' => 'o', 'п' => 'p',
            'р' => 'r', 'с' => 's', 'т' => 't', 'у' => 'u',
            'ф' => 'f', 'х' => 'h', 'ц' => 'c', 'ъ' => '',
            'ь' => '', 'ы' => 'i',
            
            'Ж' => 'Zh', 'Ч' => 'Ch', 'Щ' => 'Shh', 'Ш' => 'Sh',
            'Ю' => 'Yu', 'Ё' => 'Yo', 'Я' => 'Ya', 'Э' => 'E',
            'А' => 'A', 'Б' => 'B', 'В' => 'V',
            'Г' => 'G', 'Д' => 'D', 'Е' => 'E', 'З' => 'Z',
            'И' => 'I', 'Й' => 'Y', 'К' => 'K', 'Л' => 'L',
            'М' => 'M', 'Н' => 'N', 'О' => 'O', 'П' => 'P',
            'Р' => 'R', 'С' => 'S', 'Т' => 'T', 'У' => 'U',
            'Ф' => 'F', 'Х' => 'H', 'Ц' => 'C', 'Ъ' => '',
            'Ь' => '', 'Ы' => 'I',
        ];        
        
    }
}
<?php

namespace App\Helpers;

use App\Models\Settings;

class Setting
{
    
    public static function get($key) {
        
        $setting = Settings::select('set_value')->where('set_name', $key)->first();
        return $setting['set_value'];
        
    }
    
}
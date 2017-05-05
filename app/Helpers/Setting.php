<?php

namespace App\Helpers;

use App\Models\Settings;

use Cache;

class Setting
{
    // 10080 минут - 1 неделя
    const SETTING_CACHE_TTL = 10080;
    
    public static function get($key){
        
        $settings = Cache::remember('global.settings', self::SETTING_CACHE_TTL, function() {
            
            $globalSettings = Settings::select('set_name', 'set_value')->get()->toArray();
            foreach ($globalSettings as $Setting){
                $result[$Setting['set_name']] = $Setting['set_value'];
            }
            
            return $result;
        });
        
        return $settings[$key];
        
    }
    
}
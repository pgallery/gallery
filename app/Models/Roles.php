<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Roles extends Model
{
    
    // 10080 минут - 1 неделя
    const MODEL_CACHE_TTL = 10080;    
    
    public function users()
    {
        return \Cache::remember('RolesUsers_' . $this->id . '_cache', self::MODEL_CACHE_TTL, function(){
            return $this->belongsToMany('App\Models\User', 'roles_user');         
        });
    } 
}

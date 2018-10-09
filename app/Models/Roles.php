<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Setting;
use Cache;

class Roles extends Model
{
        
    public function users() {
        return Cache::remember('RolesUsers_' . $this->id . '_cache', Setting::get('cache_ttl'), function(){
            return $this->belongsToMany('App\Models\User', 'roles_user');         
        });
    } 
}

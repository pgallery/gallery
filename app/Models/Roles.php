<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Roles extends Model
{
    public function users()
    {
        return \Cache::remember('RolesUsers_' . $this->id . '_cache', 100, function(){
            return $this->belongsToMany('App\Models\User', 'roles_user');         
        });
    } 
}

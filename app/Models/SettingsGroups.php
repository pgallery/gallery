<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SettingsGroups extends Model
{
    public function settings()
    {
        return $this->hasMany('App\Models\Settings', 'set_group', 'id')->get();
    }
    
    public function settingsCount()
    {
        return $this->hasMany('App\Models\Settings', 'set_group', 'id')->count();
    }    
}

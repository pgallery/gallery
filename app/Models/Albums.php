<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Albums extends Model
{
    
    use SoftDeletes;    
    
    protected $dates = ['deleted_at'];
    
    protected $fillable = [
        'name', 'url', 'directory', 'images_id', 'year', 'desc', 'permission', 'groups_id', 'users_id'
    ];
    
    public function group()
    {
        return $this->hasOne('App\Models\Groups', 'id', 'groups_id');
    }
    
    public function images()
    {
        return $this->hasMany('App\Models\Images')->orderBy('name');
    }
    
    public function imagesCount()
    {
        return $this->hasMany('App\Models\Images')->count();
    }
    
    public function imagesSumSize()
    {
        return $this->hasMany('App\Models\Images')->sum('size');
    }
    
    public function owner()
    {
        return \Cache::remember(sha1('owner_' . $this->users_id . '_cache'), 100, function(){
            return $this->hasOne('App\User', 'id', 'users_id')->select('name')->first();           
        });
    }    
    
}

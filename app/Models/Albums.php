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
        return $this->hasMany('App\Models\Images');
    }
    
    public function imagesCount()
    {
        return $this->hasMany('App\Models\Images')->count();
    }    
    
    public function imagesSumSize()
    {
        return $this->hasMany('App\Models\Images')->sum('size');
    }     
}

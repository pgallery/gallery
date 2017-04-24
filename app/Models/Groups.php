<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Groups extends Model
{

    use SoftDeletes;    
    
    protected $dates = ['deleted_at'];    
    
    protected $fillable = [
        'name', 'users_id'
    ];    
    
    public function albumCount()
    {
        return \Cache::remember(sha1('albumCount_' . $this->id . '_cache'), 100, function(){
            return $this->hasMany('App\Models\Albums')->count();           
        });
    }
    
    public function albumCountPublic()
    {
        return \Cache::remember(sha1('albumCountPublic_' . $this->id . '_cache'), 100, function(){
            return $this->hasMany('App\Models\Albums')->where('albums.permission', 'All')->count();           
        });
    }     
    
    public function albumCountPrivate()
    {
        return \Cache::remember(sha1('albumCountPrivate_' . $this->id . '_cache'), 100, function(){
            return $this->hasMany('App\Models\Albums')->where('albums.permission', '!=', 'All')->count();           
        });
    }      
}

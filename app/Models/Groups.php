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
        return $this->hasMany('App\Models\Albums')->count();
    }      
    
    public function albumCountPublic()
    {
        return $this->hasMany('App\Models\Albums')->where('albums.permission', 'All')->count();
    }     
    
    public function albumCountPrivate()
    {
        return $this->hasMany('App\Models\Albums')->where('albums.permission', '!=', 'All')->count();
    }      
}

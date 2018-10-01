<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tags extends Model
{
    use SoftDeletes;    
    
    protected $dates = ['deleted_at'];    
    
    protected $fillable = [
        'name'
    ];
    
    public function albums()
    {
        return $this->belongsToMany('App\Models\Albums', 'tags_albums')->orderBy('year', 'DESC')->orderBy('created_at', 'DESC');        
    }
    
    public function albumsCount()
    {
        return $this->belongsToMany('App\Models\Albums', 'tags_albums')->where('albums.permission', 'All')->count();
    }    
}

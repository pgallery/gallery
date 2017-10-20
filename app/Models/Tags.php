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
//        return \Cache::remember('tags.album_' . $this->id . '_cache', 100, function(){
            return $this->belongsToMany('App\Models\Albums', 'tags_albums')->where('permission', 'All');        
//        });
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Cache;

class Tags extends Model
{
    use SoftDeletes;    
    
    // 10080 минут - 1 неделя
    const MODEL_CACHE_TTL = 10080;
    
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
    
    public function albumsCountRelation() {
        return Cache::remember('tagsCountRelation.albums_' . $this->id . '_cache', self::MODEL_CACHE_TTL, function () {
            return $this->albumsCount();
        });
    }
    
}

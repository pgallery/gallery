<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Setting;
use Cache;

class Tags extends Model
{
    use SoftDeletes;
    
    protected $dates = ['deleted_at'];
    
    protected $fillable = [
        'name'
    ];
    
    public function albums() {
        return $this->belongsToMany('App\Models\Albums', 'tags_albums')->orderBy('created_at', 'DESC');
    }
    
    public function albumsRelation() {
        return Cache::remember('tags.albums_' . $this->id . '_cache', Setting::get('cache_ttl'), function () {
            return $this->albums()->select('name')->orderBy('name')->get();
        });
    }
    
    public function albumsCount() {
        return $this->belongsToMany('App\Models\Albums', 'tags_albums')->where('albums.permission', 'All')->count();
    }
    
    public function albumsCountRelation() {
        return Cache::remember('tags.albumsCount_' . $this->id . '_cache', Setting::get('cache_ttl'), function () {
            return $this->albumsCount();
        });
    }
    
}

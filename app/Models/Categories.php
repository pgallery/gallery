<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Cache;

class Categories extends Model
{
    use SoftDeletes;
    
    // 10080 минут - 1 неделя
    const MODEL_CACHE_TTL = 10080;    
    
    protected $dates = ['deleted_at'];    
    
    protected $fillable = [
        'name', 'users_id'
    ];
    
    public function albums()
    {
        return $this->hasMany('App\Models\Albums')->orderBy('year', 'DESC')->orderBy('created_at', 'DESC');        
    }
    
    public function albumCount()
    {
        return Cache::remember('categories.albumCount_' . $this->id . '_cache', self::MODEL_CACHE_TTL, function(){
            return $this->hasMany('App\Models\Albums')->count();           
        });
    }

    public function albumActiveCount()
    {
        return Cache::remember('categories.albumActiveCount_' . $this->id . '_cache', self::MODEL_CACHE_TTL, function(){
            return $this->hasMany('App\Models\Albums')->where('images_id', '!=', '0')->count();           
        });
    }
    
    public function albumCountPublic()
    {
        return Cache::remember('categories.albumCountPublic_' . $this->id . '_cache', self::MODEL_CACHE_TTL, function(){
            return $this->hasMany('App\Models\Albums')->where('albums.permission', 'All')->count();           
        });
    }     

    public function albumActiveCountPublic()
    {
        return Cache::remember('categories.albumActiveCountPublic_' . $this->id . '_cache', self::MODEL_CACHE_TTL, function(){
            return $this->hasMany('App\Models\Albums')->where('images_id', '!=', '0')->where('albums.permission', 'All')->count();           
        });
    }    
    
    public function albumCountPrivate()
    {
        return Cache::remember('categories.albumCountPrivate_' . $this->id . '_cache', self::MODEL_CACHE_TTL, function(){
            return $this->hasMany('App\Models\Albums')->where('albums.permission', '!=', 'All')->count();           
        });
    }
    
    public function owner()
    {
        return Cache::remember('categories.owner_' . $this->users_id . '_cache', self::MODEL_CACHE_TTL, function(){
            return $this->hasOne('App\Models\User', 'id', 'users_id')->select('name')->first();           
        });
    }    
    
    public static function deleteWithAlbums($id) {
        
        $Albums = Albums::where('categories_id', $id)->get();
        foreach ($Albums as $album){
            Albums::deleteWithImages($album->id);
        }
        
        self::destroy($id);
        
        Cache::flush();
        
    }
    
    public function destroyCategorie($id){
        
        $category = $this->withTrashed()->findOrFail($id);
        $category->forceDelete();
        
    }    
}

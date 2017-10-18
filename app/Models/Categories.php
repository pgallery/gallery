<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Categories extends Model
{
    use SoftDeletes;
    
    protected $dates = ['deleted_at'];    
    
    protected $fillable = [
        'name', 'users_id'
    ];
    
    public function albumCount()
    {
        return \Cache::remember('categories.albumCount_' . $this->id . '_cache', 100, function(){
            return $this->hasMany('App\Models\Albums')->count();           
        });
    }
    
    public function albumCountPublic()
    {
        return \Cache::remember('categories.albumCountPublic_' . $this->id . '_cache', 100, function(){
            return $this->hasMany('App\Models\Albums')->where('albums.permission', 'All')->count();           
        });
    }     
    
    public function albumCountPrivate()
    {
        return \Cache::remember('categories.albumCountPrivate_' . $this->id . '_cache', 100, function(){
            return $this->hasMany('App\Models\Albums')->where('albums.permission', '!=', 'All')->count();           
        });
    }
    
    public function owner()
    {
        return \Cache::remember('categories.owner_' . $this->users_id . '_cache', 100, function(){
            return $this->hasOne('App\Models\User', 'id', 'users_id')->select('name')->first();           
        });
    }    
    
    public static function deleteWithAlbums($id) {
        
        $Albums = Albums::where('categories_id', $id)->get();
        foreach ($Albums as $album){
            Albums::deleteWithImages($album->id);
        }
        
        self::destroy($id);
        
        \Cache::flush();
        
    }
    
    public function destroyCategorie($id){
        
        $group = $this->withTrashed()->findOrFail($id);
        $group->forceDelete();
        
    }    
}

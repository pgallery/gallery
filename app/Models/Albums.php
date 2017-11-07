<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Cache;
use Storage;

class Albums extends Model
{
    
    use SoftDeletes;    
    
    protected $dates = ['deleted_at'];
    
    protected $fillable = [
        'name', 'url', 'directory', 'images_id', 'year', 'desc', 'permission', 'password', 'categories_id', 'users_id'
    ];

    public function http() {
        return Cache::remember('albums.http_' . $this->id . '_cache', 100, function(){
            return env('APP_URL') . "/images/original/" . $this->url;
        });
    }
    
    public function path() {
        return Cache::remember('albums.path_' . $this->id . '_cache', 100, function(){
            return \Setting::get('upload_dir') . "/" .  $this->directory;
        });
    }

    public function http_path() {
        return Cache::remember('albums.http_path_' . $this->id . '_cache', 100, function(){
            return env('APP_URL') . "/images/original/" . $this->url;
        });
    }    
    
    public function thumb_path() {
        return Cache::remember('albums.thumb_path_' . $this->id . '_cache', 100, function(){
            return \Setting::get('thumbs_dir') . "/" .  $this->directory;
        });
    }

    public function http_thumb_path() {
        return Cache::remember('albums.http_thumb_path_' . $this->id . '_cache', 100, function() {
            return env('APP_URL') . "/images/thumb/" . $this->url;
        });
    }
    
    public function mobile_path() {
        return Cache::remember('albums.mobile_path_' . $this->id . '_cache', 100, function(){
            return \Setting::get('mobile_upload_dir') . "/" .  $this->directory;
        });
    }

    public function http_mobile_path() {
        return Cache::remember('albums.http_mobile_path_' . $this->id . '_cache', 100, function(){
            return env('APP_URL') . "/images/mobile/" . $this->url;
        });
    }
    
    public function category()
    {
        return Cache::remember('albums.category_' . $this->categories_id . '_cache', 100, function(){
            return $this->hasOne('App\Models\Categories', 'id', 'categories_id')->select('name')->first();
        });
    }
    
    public function images()
    {
        return $this->hasMany('App\Models\Images')->orderBy('name');
    }
    
    public function imagesCount()
    {
        return Cache::remember('albums.imagesCount_' . $this->id . '_cache', 100, function(){
            return $this->hasMany('App\Models\Images')->count();
        });
    }
    
    public function imagesSumSize()
    {
        return Cache::remember('albums.imagesSumSize_' . $this->id . '_cache', 100, function(){
            return \App\Helpers\Format::Bytes($this->hasMany('App\Models\Images')->sum('size'));
        });
    }
    
    public function owner()
    {
        return Cache::remember('albums.owner_' . $this->users_id . '_cache', 100, function(){
            return $this->hasOne('App\Models\User', 'id', 'users_id')->select('id', 'name')->first();           
        });
    }
    
    public function thumbs() {
        return $this->hasOne('App\Models\Images', 'id', 'images_id');
    }

    public function tags() {
        return $this->belongsToMany('App\Models\Tags', 'tags_albums');
    }
    
    public static function deleteWithImages($id) {
               
        self::destroy($id);
        Images::where('albums_id', $id)->delete();  
        
        Cache::flush();

    }
    
    public function destroyAlbum($id){
        
        $album = $this->withTrashed()->findOrFail($id);
        
        if(Storage::has($album->thumb_path()))
            Storage::deleteDirectory($album->thumb_path());
        
        if(Storage::has($album->mobile_path()))
            Storage::deleteDirectory($album->mobile_path());        

        if(Storage::has($album->path()))
            Storage::deleteDirectory($album->path());         

        $album->forceDelete();
        
    }
}

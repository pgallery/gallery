<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Albums extends Model
{
    
    use SoftDeletes;    
    
    protected $dates = ['deleted_at'];
    
    protected $fillable = [
        'name', 'url', 'directory', 'images_id', 'year', 'desc', 'permission', 'categories_id', 'users_id'
    ];
    
    public function path() {
        return public_path(\Setting::get('upload_dir') . "/" .  $this->directory);
    }

    public function thumb_path() {
        return public_path(\Setting::get('thumbs_dir') . "/" .  $this->directory);
    }

    public function mobile_path() {
        return public_path(\Setting::get('mobile_upload_dir') . "/" .  $this->directory);
    }
    
    public function category()
    {
        return \Cache::remember('albums.category_' . $this->categories_id . '_cache', 100, function(){
            return $this->hasOne('App\Models\Categories', 'id', 'categories_id')->select('name')->first();
        });
    }
    
    public function images()
    {
        return $this->hasMany('App\Models\Images')->orderBy('name');
    }
    
    public function imagesCount()
    {
        return \Cache::remember('albums.imagesCount_' . $this->id . '_cache', 100, function(){
            return $this->hasMany('App\Models\Images')->count();
        });
    }
    
    public function imagesSumSize()
    {
        return \Cache::remember('albums.imagesSumSize_' . $this->id . '_cache', 100, function(){
            return \Helper::formatBytes($this->hasMany('App\Models\Images')->sum('size'));
        });
    }
    
    public function owner()
    {
        return \Cache::remember('albums.owner_' . $this->users_id . '_cache', 100, function(){
            return $this->hasOne('App\Models\User', 'id', 'users_id')->select('id', 'name')->first();           
        });
    }
    
    public function thumbs() {
        return \Cache::remember('albums.thumbs_' . $this->images_id . '_cache', 100, function(){
            return $this->hasOne('App\Models\Images', 'id', 'images_id')->select('name')->first();
        });
    }

    public function tags() {
        return \Cache::remember('albums.tags_' . $this->images_id . '_cache', 100, function(){
            return $this->belongsToMany('App\Models\Tags');
        });
    }
    
    public static function deleteWithImages($id) {
               
        self::destroy($id);
        Images::where('albums_id', $id)->delete();  
        
        \Cache::flush();

    }
    
    public function destroyAlbum($id){
        
        $album = $this->onlyTrashed()->where('id', $id)->first();
        
        $upload_path = $this->path();
        $mobile_path = $this->mobile_path();
        $thumb_path  = $this->thumb_path();
        
        if(\File::isDirectory($upload_path))
            \File::deleteDirectory($upload_path);
            
        if(\File::isDirectory($mobile_path))
            \File::deleteDirectory($mobile_path);

        if(\File::isDirectory($thumb_path))
            \File::deleteDirectory($thumb_path);         
        
        $album = $this->withTrashed()->findOrFail($id);
        $album->forceDelete();
        
    }
}

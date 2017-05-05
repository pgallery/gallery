<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\Images;

class Albums extends Model
{
    
    use SoftDeletes;    
    
    protected $dates = ['deleted_at'];
    
    protected $fillable = [
        'name', 'url', 'directory', 'images_id', 'year', 'desc', 'permission', 'groups_id', 'users_id'
    ];
    
    public function group()
    {
        return \Cache::remember('albums.group_' . $this->groups_id . '_cache', 100, function(){
            return $this->hasOne('App\Models\Groups', 'id', 'groups_id')->select('name')->first();
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
            return $this->hasMany('App\Models\Images')->sum('size');
        });
    }
    
    public function owner()
    {
        return \Cache::remember('owner_' . $this->users_id . '_cache', 100, function(){
            return $this->hasOne('App\Models\User', 'id', 'users_id')->select('name')->first();           
        });
    }
    
    public function thumbs() {
        return \Cache::remember('albums.thumbs_' . $this->images_id . '_cache', 100, function(){
            return $this->hasOne('App\Models\Images', 'id', 'images_id')->select('name')->first();
        });
    }
    
    public static function deleteWithImages($id) {
               
        self::destroy($id);
        Images::where('albums_id', $id)->delete();  
        
        \Cache::flush();

    }
    
    public function destroyAlbum($id){
        
        $album = $this->onlyTrashed()->where('id', $id)->first();
        
        $upload_path = \Helper::getUploadPath($id) . $album->directory;
        $mobile_path = \Helper::getFullPathMobile($id) . $album->directory;
        $thumb_path  = \Helper::getFullPathThumb($id) . $album->directory;
        
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

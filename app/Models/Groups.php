<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

//use App\Models\Albums;

class Groups extends Model
{

    use SoftDeletes;    
    
    protected $dates = ['deleted_at'];    
    
    protected $fillable = [
        'name', 'users_id'
    ];    
    
    public function albumCount()
    {
        return \Cache::remember(sha1('albumCount_' . $this->id . '_cache'), 100, function(){
            return $this->hasMany('App\Models\Albums')->count();           
        });
    }
    
    public function albumCountPublic()
    {
        return \Cache::remember(sha1('albumCountPublic_' . $this->id . '_cache'), 100, function(){
            return $this->hasMany('App\Models\Albums')->where('albums.permission', 'All')->count();           
        });
    }     
    
    public function albumCountPrivate()
    {
        return \Cache::remember(sha1('albumCountPrivate_' . $this->id . '_cache'), 100, function(){
            return $this->hasMany('App\Models\Albums')->where('albums.permission', '!=', 'All')->count();           
        });
    }
    
    public static function deleteWithAlbums($id) {
        
        $Albums = Albums::where('groups_id', $id)->get();
        foreach ($Albums as $album){
            Albums::deleteWithImages($album->id);
        }
        
        self::destroy($id);
        
        \Cache::flush();
        
//        \Cache::forget(sha1('Cache.App.Helpers.Viewer'));
//        \Cache::forget(sha1('admin.show.groups'));        
        
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Cache;
use Storage;

class Images extends Model
{
    
    use SoftDeletes;
    
    // 10080 минут - 1 неделя
    const MODEL_CACHE_TTL = 10080;     
    
    protected $dates = ['deleted_at'];
    
    protected $fillable = [
        'name', 'size', 'height', 'width', 'albums_id', 'users_id', 'is_rebuild'
    ];
    
    public function album() {
        return $this->hasOne('App\Models\Albums', 'id', 'albums_id')->withTrashed();
    }
    
    public function path() {
        return $this->album->path() . '/' . $this->name;
    }

    public function http_path() {
        return $this->album->http_path() . '/' . $this->name;
    }
    
    public function thumb_path() {
        return $this->album->thumb_path() . '/' . $this->name;
    }

    public function http_thumb_path() {
        return $this->album->http_thumb_path() . '/' . $this->name;
    }
    
    public function mobile_path() {
        return $this->album->mobile_path() . '/' . $this->name;
    }

    public function http_mobile_path() {
        return $this->album->http_mobile_path() . '/' . $this->name;
    } 
    
    public function owner() {
        return Cache::remember('images.owner_' . $this->users_id . '_cache', self::MODEL_CACHE_TTL, function(){
            return $this->hasOne('App\Models\User', 'id', 'users_id')->select('id', 'name')->first();           
        });
    }
    
    public function deleteCheckAlbumPreview($id) {
        
        $album_id = $this->find($id)->album->id;
        $this->destroy($id);
        
        if(Albums::find($album_id)->where('images_id', $id)->count() == 1) {
            if($this->where('albums_id', $album_id)->count() == 0) {
                Albums::find($album_id)->update(['images_id' => 0]);
            } else {
                $Images = $this->where('albums_id', $album_id)->first();
                Albums::find($album_id)->update(['images_id' => $Images->id]);
            }
        }
        
        Cache::flush();
        
    }
    
    public function destroyImage($id){
        
        $image = $this->withTrashed()->findOrFail($id);
        
        if(Storage::has($image->thumb_path()))
            Storage::delete($image->thumb_path());
        
        if(Storage::has($image->mobile_path()))
            Storage::delete($image->mobile_path());        

        if(Storage::has($image->path()))
            Storage::delete($image->path());  
        
        $image->forceDelete();
        
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Images extends Model
{
    
    use SoftDeletes;
    
    protected $dates = ['deleted_at'];
    
    protected $fillable = [
        'name', 'size', 'albums_id', 'users_id', 'is_rebuild', 'is_thumbs', 'is_modile'
    ];
    
    public function album()
    {
        return $this->hasOne('App\Models\Albums', 'id', 'albums_id')->withTrashed();
    }
    
    public function owner()
    {
        return \Cache::remember('owner_' . $this->users_id . '_cache', 100, function(){
            return $this->hasOne('App\Models\User', 'id', 'users_id')->select('id', 'name')->first();           
        });
    }
    
    public static function deleteCheckAlbumPreview($id) {
        
        $album_id = self::find($id)->album->id;
        self::destroy($id);
        
        if(Albums::find($album_id)->where('images_id', $id)->count() == 1) {
            if(self::where('albums_id', $album_id)->count() == 0) {
                Albums::find($album_id)->update(['images_id' => 0]);
            } else {
                $Images = self::where('albums_id', $album_id)->first();
                Albums::find($album_id)->update(['images_id' => $Images->id]);
            }
        }
        
        \Cache::flush();
        
    }
    
    public function destroyImage($id){
        
        $full_image   = \Helper::getFullPathImage($id);
        $mobile_image = \Helper::getFullPathMobileImage($id);
        $thumb_image  = \Helper::getFullPathThumbImage($id);
        
        if (\File::exists($full_image))
            \File::delete($full_image);
        
        if (\File::exists($mobile_image))
            \File::delete($mobile_image);
        
        if (\File::exists($thumb_image))
            \File::delete($thumb_image);        
        
        $image = $this->withTrashed()->findOrFail($id);
        $image->forceDelete();
        
    }
}

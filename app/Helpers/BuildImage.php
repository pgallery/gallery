<?php

namespace App\Helpers;

use App\Models\Albums;
use App\Models\Images;

use Setting;
use File;
use Storage;
use Image;
use Cache;

class BuildImage
{
    
    public static function run($id) {

        $image = Images::find($id);
        
        if(Storage::has($image->thumb_path()))
            Storage::delete($image->thumb_path());
        
        if(Storage::has($image->mobile_path()))
            Storage::delete($image->mobile_path());
        
        if(!Storage::has($image->album->thumb_path()))
            Storage::makeDirectory($image->album->thumb_path(), 0755, true);
        
        if(!Storage::has($image->album->mobile_path()))
            Storage::makeDirectory($image->album->mobile_path(), 0755, true);
        
        $OriginalImage = Image::make(Storage::get($image->path()));

        $imgHeight       = $OriginalImage->height();
        $imgWidth        = $OriginalImage->width();
        
        // Делаем версию изображения для мобильных устройств
        $OriginalImage->resize(Setting::get('mobile_width'), null, function ($constraint) {
                $constraint->aspectRatio();
            });         
        
        Storage::put($image->mobile_path(), (string) $OriginalImage->encode());    
        
        // Делаем превью
        $OriginalImage->fit(Setting::get('thumbs_width'), Setting::get('thumbs_height'));
        
        Storage::put($image->thumb_path(), (string) $OriginalImage->encode());  
        
        $OriginalImage->destroy();
        
        $image->update([
            'size'          => Storage::size($image->path()),
            'height'        => $imgHeight,
            'width'         => $imgWidth,
            'thumbs_size'   => Storage::size($image->thumb_path()),
            'modile_size'   => Storage::size($image->mobile_path()),
            'is_rebuild'    => 0,
        ]);
        
        if(Setting::get('saveinpublic_thumbs') == 'yes'){
            
            $web_album_path = public_path("images/thumb/" . $image->album->url);

            if (!File::isDirectory($web_album_path))
                File::makeDirectory($web_album_path, 0755, true);
            
            File::put($web_album_path . "/" . $image->name, Storage::get($image->thumb_path()));
            
        }

        if(Setting::get('saveinpublic_mobiles') == 'yes'){
            
            $web_album_path = public_path("images/mobile/" . $image->album->url);

            if (!File::isDirectory($web_album_path))
                File::makeDirectory($web_album_path, 0755, true);
            
            File::put($web_album_path . "/" . $image->name, Storage::get($image->mobile_path()));
            
        }
        
        Cache::forget('albums.imagesRebuildCount_' . $image->albums_id . '_cache');
        
    }
    
}
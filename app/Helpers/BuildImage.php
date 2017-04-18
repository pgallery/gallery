<?php

namespace App\Helpers;

use App\Models\Albums;
use App\Models\Images;
//
use Helper;
use Setting;
use File;
use Image;

class BuildImage
{
    
    public static function run($id) {
        
        $album = Images::find($id)->album;
        
        $file_path   = Helper::getFullPathImage($id);
        $thumbs_path = Helper::getFullPathThumb($album->id);
        $thumbs_file = Helper::getFullPathThumbImage($id);
        $modile_path = Helper::getFullPathMobile($album->id);
        $modile_file = Helper::getFullPathMobileImage($id);          
        
        if (File::exists($thumbs_file))
            File::delete($thumbs_file);

        if (File::exists($modile_file))
            File::delete($modile_file);        
        
        if(!File::isDirectory($thumbs_path))
            File::makeDirectory($thumbs_path, 0755, true);

        if(!File::isDirectory($modile_path))
            File::makeDirectory($modile_path, 0755, true);           

        // Делаем превью
        Image::make($file_path)
            ->fit(Setting::get('thumbs_width'), Setting::get('thumbs_height'))
            ->save($thumbs_file);

        // Делаем версию изображения для мобильных устройств
        Image::make($file_path)
            ->resize(Setting::get('mobile_width'), null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($modile_file);        
        
        $SizeImage = Image::make($file_path)->filesize();
        $ThumbsSizeImage = Image::make($thumbs_file)->filesize();
        $MobileSizeImage = Image::make($modile_file)->filesize();    

        Images::where('id', $id)->update([
            'size'          => $SizeImage,
            'thumbs_size'   => $ThumbsSizeImage,
            'modile_size'   => $MobileSizeImage,
            'is_rebuild'    => 0,
            'is_thumbs'     => 1,
            'is_modile'     => 1,
        ]);
        
    }
    
}
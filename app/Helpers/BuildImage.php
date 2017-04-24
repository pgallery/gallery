<?php

namespace App\Helpers;

use App\Models\Albums;
use App\Models\Images;

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
        
        $OriginalImage = Image::make($file_path);
        
        $imgSize         = $OriginalImage->filesize();
        $imgHeight       = $OriginalImage->height();
        $imgWidth        = $OriginalImage->width();        
        
        // Делаем версию изображения для мобильных устройств
        $OriginalImage->resize(Setting::get('mobile_width'), null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($modile_file);         
        
        $MobileSizeImage = $OriginalImage->filesize();    
        
        // Делаем превью
        $OriginalImage->fit(Setting::get('thumbs_width'), Setting::get('thumbs_height'))
            ->save($thumbs_file);

        $ThumbsSizeImage = $OriginalImage->filesize(); 

        Images::where('id', $id)->update([
            'size'          => $imgSize,
            'height'        => $imgHeight,
            'width'         => $imgWidth,
            'thumbs_size'   => $ThumbsSizeImage,
            'modile_size'   => $MobileSizeImage,
            'is_rebuild'    => 0,
            'is_thumbs'     => 1,
            'is_modile'     => 1,
        ]);
        
    }
    
}
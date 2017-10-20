<?php

namespace App\Helpers;

use App\Models\Albums;
use App\Models\Images;

use Setting;
use File;
use Image;

class BuildImage
{
    
    public static function run($id) {

        $image = Images::find($id);
        
        $file_path   = $image->path();
        $thumbs_path = $image->album->thumb_path();
        $thumbs_file = $image->thumb_path();
        $modile_path = $image->album->mobile_path();
        $modile_file = $image->mobile_path();
        
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

        $image->update([
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
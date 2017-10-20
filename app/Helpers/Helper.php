<?php

namespace App\Helpers;

use App\Models\User;
use App\Models\Albums;
use App\Models\Groups;
use App\Models\Images;

use Agent;
use Setting;

class Helper
{
    // Проверяет, является ли пользователь Администратором
    public static function isAdmin($user){

        $userRoles = \Cache::remember('HelperIsAdmin_' . $user . '_cache', 100, function() use ($user){
            return User::find($user)->roles->pluck('id','name')->toArray();
        });

        if(in_array("admin", array_keys($userRoles)))
            return true;
        else
            return false;

    }
    
    // Проверяет, имеет ли пользователь доступ к меню управления
    public static function isAdminMenu($user) {
        
        $userRoles = \Cache::remember('HelperIsAdminMenu_' . $user . '_cache', 100, function() use ($user){
            return User::find($user)->roles->pluck('id','topanel')->toArray();
        });
        
        if(in_array("Y", array_keys($userRoles)))
            return true;
        else
            return false;
        
    }
    
//    // Полный путь к файлу
//    // $image_id - id файла по таблице `images`
//    public static function getFullPathImage($image_id, $type = false){
//        
//        $image = Images::withTrashed()->where('id', $image_id)->first();
//        
//        $path = $image->album->directory . "/" . $image['name'];
//
//        if($type == 'url')
//        {
//            
//            if (Agent::isMobile())
//                $path = Setting::get('mobile_upload_dir') . "/" . $path;
//            else
//                $path = Setting::get('upload_dir') . "/" . $path;
//            
//            return env('APP_URL') . "/" . $path;
//        }
//        else
//        {
//            return public_path(Setting::get('upload_dir') . "/" .  $path);
//        }
//    }
    
//    // Полный путь к директории миниатюр альбома
//    // $album_id - id альбома по таблице `albums`
//    public static function getFullPathThumb($album_id){
//        
//        $album = Albums::select('directory')->find($album_id);
//        
//        return public_path(Setting::get('thumbs_dir') . "/" .  $album->directory);
//        
//    }
    
//    // Полный путь к файлу миниатюры
//    // $album_id - id альбома по таблице `albums`    
//    public static function getFullPathThumbImage($image_id, $type = false){
//        
//        $image = Images::withTrashed()->where('id', $image_id)->first();
//        
//        $path = Setting::get('thumbs_dir') . "/" .  $image->album->directory . "/" . $image['name'];
//        
//        if($type == 'url')
//            return env('APP_URL') . "/" . $path;
//        else
//            return public_path($path);        
//        
//    }
    
//    // Полный путь к директории мобильной версии альбома
//    // $album_id - id альбома по таблице `albums`
//    public static function getFullPathMobile($album_id){
//        
//        $album = Albums::select('directory')->find($album_id);
//        
//        return public_path(Setting::get('mobile_upload_dir') . "/" .  $album->directory);
//        
//    }
    
//    // Полный путь к мобильной версии файла
//    // $album_id - id альбома по таблице `albums`    
//    public static function getFullPathMobileImage($image_id, $type = false){
//        
//        $image = Images::withTrashed()->where('id', $image_id)->first();
//        
//        $path = Setting::get('mobile_upload_dir') . "/" .  $image->album->directory . "/" . $image->name;
//        
//        if($type == 'url')
//            return env('APP_URL') . "/" . $path;
//        else
//            return public_path($path);          
//        
//    }
    
//    // Полный путь к директории загрузки изображений альбома
//    // $album_id - id альбома по таблице `albums`    
//    public static function getUploadPath($album_id){
//        
//        $album = Albums::select('directory')->find($album_id);
//        
//        return public_path(Setting::get('upload_dir') . "/" .  $album->directory);        
//        
//    }
    
    // Приобразует байты в большие единицы измерения
    public static function formatBytes($size){
        if ($size > 0) {
            $size = (int) $size;
            $base = log($size) / log(1024);
            $suffixes = explode(",", Setting::get('format_bytes'));
            
            return round(pow(1024, $base - floor($base)), Setting::get('format_precision')) . $suffixes[floor($base)];
        } else {
            return $size;
        }
    }
}
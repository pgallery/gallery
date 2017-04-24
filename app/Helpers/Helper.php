<?php

namespace App\Helpers;

use App\User;
use App\Models\Albums;
use App\Models\Groups;
use App\Models\Images;

use Agent;
use Setting;

class Helper
{
    // Проверяет, является ли пользователь Администратором
    public static function isAdmin($user){

//        $userRoles = User::find($user)->roles->pluck('id','name')->toArray();
        
        $userRoles = \Cache::remember(sha1('HelperIsAdmin_' . $user . '_cache'), 100, function() use ($user){
            return User::find($user)->roles->pluck('id','name')->toArray();
        });

        if(in_array("admin", array_keys($userRoles)))
            return true;
        else
            return false;

    }
    
    // Проверяет, имеет ли пользователь доступ к меню управления
    public static function isAdminMenu($user) {
        
//        $userRoles = User::find($user)->roles->pluck('id', 'topanel')->toArray();
        $userRoles = \Cache::remember(sha1('HelperIsAdminMenu_' . $user . '_cache'), 100, function() use ($user){
            return User::find($user)->roles->pluck('id','topanel')->toArray();
        });
        
        if(in_array("Y", array_keys($userRoles)))
            return true;
        else
            return false;
        
    }
    
    // Полный путь к файлу
    // $image_id - id файла по таблице `images`
    public static function getFullPathImage($image_id, $type = false){
        
        $image = Images::withTrashed()->where('id', $image_id)->first();
        
        $path = $image->album->directory . "/" . $image['name'];
//        $path = $image['name'];
        if($type == 'url')
        {
            
            if (Agent::isMobile())
                $path = Setting::get('mobile_upload_dir') . "/" . $path;
            else
                $path = Setting::get('upload_dir') . "/" . $path;
            
            return env('APP_URL') . "/" . $path;
        }
        else
        {
            return public_path() . "/"  . Setting::get('upload_dir') . "/" .  $path;
        }
    }
    
    // Полный путь к директории миниатюр альбома
    // $album_id - id альбома по таблице `albums`
    public static function getFullPathThumb($album_id){
        
        $Albums = Albums::find($album_id);
        
        return public_path() . "/" . Setting::get('thumbs_dir') . "/" .  $Albums['directory'];
        
    }
    
    // Полный путь к файлу миниатюры
    // $album_id - id альбома по таблице `albums`    
    public static function getFullPathThumbImage($image_id, $type = false){
        
        $image = Images::withTrashed()->where('id', $image_id)->first();
        
        $path = Setting::get('thumbs_dir') . "/" .  $image->album->directory . "/" . $image['name'];
        
        if($type == 'url')
            return env('APP_URL') . "/" . $path;
        else
            return public_path() . "/" . $path;        
        
    }
    
    // Полный путь к директории мобильной версии альбома
    // $album_id - id альбома по таблице `albums`
    public static function getFullPathMobile($album_id){
        
        $Albums = Albums::find($album_id);
        
        return public_path() . "/" . Setting::get('mobile_upload_dir') . "/" .  $Albums['directory'];
        
    }
    
    // Полный путь к мобильной версии файла
    // $album_id - id альбома по таблице `albums`    
    public static function getFullPathMobileImage($image_id, $type = false){
        
        $image = Images::withTrashed()->where('id', $image_id)->first();
        
        $path = Setting::get('mobile_upload_dir') . "/" .  $image->album->directory . "/" . $image['name'];
        
        if($type == 'url')
            return env('APP_URL') . "/" . $path;
        else
            return public_path() . "/" . $path;          
        
    }
    
    // Полный путь к директории загрузки изображений альбома
    // $album_id - id альбома по таблице `albums`    
    public static function getUploadPath($album_id){
        
        $Albums = Albums::find($album_id);
        
        return public_path() . "/" . Setting::get('upload_dir') . "/" .  $Albums['directory'];        
        
    }
}
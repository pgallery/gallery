<?php

namespace App\Helpers;

use App\Models\User;
use App\Models\Categories;
use App\Models\Albums;
use App\Models\Images;

use Auth;
use Cache;
use Setting;
use Roles;

class Viewer
{
    public static function get($page, $data = false) {
        
        if(Roles::is('admin'))
            $CacheKey = 'Admin.';
        else
            $CacheKey = 'User.';        
        
        $CacheKey .= 'Cache.App.Helpers.Viewer';
        
        if (Cache::has($CacheKey)) {
            
            $static = Cache::get($CacheKey);
            
        } else {
            
            if(Roles::is('admin'))
                $year_list = Albums::select('year')->groupBy('year')->get();
            else
                $year_list = Albums::select('year')->where('permission', 'All')->groupBy('year')->get();            
            
            $static = [
                'categories_count' => Categories::All()->count(),
                'categories'       => Categories::orderBy('name')->get(),
                'year_list'        => $year_list, 
                'gallery_name'     => Setting::get('gallery_name'),
            ];
            
            if(Roles::is(['admin', 'moderator'])) {

                $CountTrashedUsers      = User::onlyTrashed()->count();
                $CountTrashedCategories = Categories::onlyTrashed()->count();
                $CountTrashedAlbums     = Albums::onlyTrashed()->count();
                $CountTrashedImages     = Images::onlyTrashed()->count();
            
                $TrashedMenu = [
                    'summary_trashed'       => $CountTrashedUsers + $CountTrashedCategories + $CountTrashedAlbums + $CountTrashedImages,
                    'users_trashed'         => $CountTrashedUsers,
                    'categories_trashed'    => $CountTrashedCategories,
                    'albums_trashed'        => $CountTrashedAlbums,
                    'images_trashed'        => $CountTrashedImages,
                ];                
                
                $static = array_merge($TrashedMenu, $static); 
                
            }     
            
            Cache::add($CacheKey, $static, Setting::get('cache_ttl'));
        }

        if(!$data)
            $result = $static;
        else
            $result = array_merge($data, $static);
        
        return view('default/' . $page, $result);
        
    }
    
}
<?php

namespace App\Helpers;

use App\Models\User;
use App\Models\Categories;
use App\Models\Albums;
use App\Models\Images;

use Auth;
use Cache;
use Setting;
use Helper;

class Viewer
{
    public static function get($page, $data = false) {
        
        if(Auth::check() and Helper::isAdminMenu(Auth::user()->id))
            $CacheKey = 'Admin.';
        else
            $CacheKey = 'User.';        
        
        $CacheKey .= 'Cache.App.Helpers.Viewer';
        
        if (Cache::has($CacheKey)) {
            
            $static = Cache::get($CacheKey);
            
        } else {
            
            
            if(Auth::check() and Helper::isAdmin(Auth::user()->id))
                $year_list = Albums::select('year')->groupBy('year')->get();
            else
                $year_list = Albums::select('year')->where('permission', 'All')->groupBy('year')->get();            
            
            $static = [
                'categories_count' => Categories::All()->count(),
                'categories'       => Categories::orderBy('name')->get(),
                'year_list'        => $year_list, 
                'gallery_name'     => Setting::get('gallery_name'),
            ];
            
            if(Auth::check() and Helper::isAdminMenu(Auth::user()->id)) {

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
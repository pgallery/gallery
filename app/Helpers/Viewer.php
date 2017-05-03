<?php

namespace App\Helpers;

use App\User;
use App\Models\Groups;
use App\Models\Albums;
use App\Models\Images;

use Auth;
use Cache;
use Setting;
use Helper;

class Viewer
{
    public static function get($page, $data = false) {
        
        if(Auth::check() and Helper::isAdmin(Auth::user()->id))
            $preCacheKey = 'Admin.';
        else
            $preCacheKey = 'User.';        
        
        $preCacheKey .= 'Cache.App.Helpers.Viewer';
        $CacheKey = sha1($preCacheKey);
        
        if (Cache::has($CacheKey))
        {
            $static = Cache::get($CacheKey);
        }
        else
        {
            $CountTrashedUsers  = User::onlyTrashed()->count();
            $CountTrashedGroups = Groups::onlyTrashed()->count();
            $CountTrashedAlbums = Albums::onlyTrashed()->count();
            $CountTrashedImages = Images::onlyTrashed()->count();

            if(Auth::check() and Helper::isAdmin(Auth::user()->id))
                $year_list = Albums::select('year')->groupBy('year')->get();
            else
                $year_list = Albums::select('year')->where('permission', 'All')->groupBy('year')->get();            
            
            $static = [
                'group_list'        => Groups::orderBy('name')->get(),
                'year_list'         => $year_list, 
                'summary_trashed'   => $CountTrashedUsers + $CountTrashedGroups + $CountTrashedAlbums + $CountTrashedImages,
                'users_trashed'     => $CountTrashedUsers,
                'groups_trashed'    => $CountTrashedGroups,
                'albums_trashed'    => $CountTrashedAlbums,
                'images_trashed'    => $CountTrashedImages,
                'gallery_name'      => Setting::get('gallery_name'),
            ];
            
            Cache::add($CacheKey, $static, Setting::get('cache_ttl'));
        }

        if(!$data)
            $result = $static;
        else
            $result = array_merge($data, $static);
        
        return view($page, $result);
        
    }
    
}
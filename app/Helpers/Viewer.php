<?php

namespace App\Helpers;

use App\Models\Groups;
use App\Models\Albums;
use App\Models\Images;

use Cache;
use Setting;

class Viewer
{
    public static function get($page, $data = false) {
        
        $CacheKey = sha1('Cache.App.Helpers.Viewer');
        if (Cache::has($CacheKey))
        {
            $static = Cache::get($CacheKey);
        }
        else
        {
            $CountTrashedGroups = Groups::onlyTrashed()->count();
            $CountTrashedAlbums = Albums::onlyTrashed()->count();
            $CountTrashedImages = Images::onlyTrashed()->count();

            $static = [
                'group_list'        => Groups::all(), 
                'year_list'         => Albums::select('year')->where('permission', 'All')->groupBy('year')->get(), 
                'summary_trashed'   => $CountTrashedGroups + $CountTrashedAlbums + $CountTrashedImages,
                'groups_trashed'    => $CountTrashedGroups,
                'albums_trashed'    => $CountTrashedAlbums,
                'images_trashed'    => $CountTrashedImages,
                
                'gallery_name'      => Setting::get('gallery_name'),
            ];
            
            Cache::add($CacheKey, $static, Setting::get('cache_ttl'));
        }

        if($data == false)
            $result = $static;
        else
            $result = array_merge($data, $static);
        
        return view($page, $result);
        
    }
    
}
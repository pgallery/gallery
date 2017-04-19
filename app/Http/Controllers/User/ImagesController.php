<?php

namespace App\Http\Controllers\User;

use Illuminate\Routing\Router;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Albums;
use App\Models\Images;

use Agent;
use Helper;
use Setting;
use Viewer;
use Cache;

class ImagesController extends Controller
{
    
    public function getShow(Router $router)
    {
        
        if(Albums::where('url', $router->input('url'))->count() == 0)
            return Viewer::get('errors.404');
        
        $thisAlbum = Albums::where('url', $router->input('url'))->first();
        
        if (Agent::isMobile())
            $CacheKey = sha1($router->input('url') . '.Mobile');
        else
            $CacheKey = sha1($router->input('url')); 

        if (Cache::has($CacheKey))
        {
            $resultData = Cache::get($CacheKey);
        }
        else
        {
            
            $thumbs_width  = Setting::get('thumbs_width');
            $thumbs_height = Setting::get('thumbs_height');
            $thumbs_dir    = Setting::get('thumbs_dir');

            if (Agent::isMobile())
                $upload_dir = Setting::get('mobile_upload_dir');
            else
                $upload_dir = Setting::get('upload_dir');            
            
            $listImages = $thisAlbum
                    ->images()
                    ->paginate(Setting::get('count_images'));

            $resultData = compact(
                'upload_dir',
                'thumbs_width',
                'thumbs_height',
                'thumbs_dir',
                'thisAlbum',
                'listImages'
            );
            
            Cache::add($CacheKey, $resultData, Setting::get('cache_ttl'));
        }
        
        return Viewer::get('pages.images', $resultData);

    }
    
    public function getShowPage(Router $router, Request $request) {
        
        \Debugbar::disable();
        
        if(Albums::where('url', $router->input('url'))->count() == 0)
            return Viewer::get('errors.404');
        
        $thisAlbum = Albums::where('url', $router->input('url'))->first();        
        
        if (Agent::isMobile())
            $CacheKey = sha1($router->input('url') . '.Mobile' . $request->input('page'));
        else
            $CacheKey = sha1($router->input('url') . $request->input('page'));

        if (Cache::has($CacheKey))
        {
            $resultData = Cache::get($CacheKey);
        }
        else
        {
        
            $thumbs_width  = Setting::get('thumbs_width');
            $thumbs_height = Setting::get('thumbs_height');
            $thumbs_dir    = Setting::get('thumbs_dir');

            if (Agent::isMobile())
                $upload_dir = Setting::get('mobile_upload_dir');
            else
                $upload_dir = Setting::get('upload_dir');         

            $listImages = $thisAlbum
                    ->images()
                    ->paginate(Setting::get('count_images'));

            $resultData = compact(
                'upload_dir',
                'thumbs_width',
                'thumbs_height',
                'thumbs_dir',
                'thisAlbum',
                'listImages'
            );
            
            Cache::add($CacheKey, $resultData, Setting::get('cache_ttl'));
        }
        
        return Viewer::get('pages.images_page', $resultData);
        
    }
    
}

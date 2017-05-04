<?php

namespace App\Http\Controllers\User;

use Illuminate\Routing\Router;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Albums;

use Auth;
use Agent;
use Helper;
use Setting;
use Viewer;
use Cache;

class ImagesController extends Controller
{
    protected $albums;

    public function __construct(Albums $albums) {
        $this->albums  = $albums;
    }
    
    public function getPage(Router $router, Request $request) {
        
        $url = $router->input('url');
        
        $thisAlbum = Cache::remember(sha1('albums.show.' . $url), Setting::get('cache_ttl'), function() use ($url){
            return $this->albums->where('url', $url)->first();
        });
        
        if(!$thisAlbum)
            return Viewer::get('errors.404');
        
        if (Agent::isMobile() and !Auth::check())
            $CacheKey = sha1($url . '.Mobile' . $request->input('page'));
        elseif(Agent::isMobile() and Auth::check() and Helper::isAdmin(Auth::user()->id))
            $CacheKey = sha1($url . '.AdminMobile' . $request->input('page'));
        elseif(!Agent::isMobile() and Auth::check() and Helper::isAdmin(Auth::user()->id))
            $CacheKey = sha1($url . '.Admin' . $request->input('page'));        
        else
            $CacheKey = sha1($url . $request->input('page'));
        
        if (Cache::has($CacheKey))
        {
            $resultData = Cache::get($CacheKey);
        }
        else
        {
        
            $thumbs_width  = Setting::get('thumbs_width');
            $thumbs_height = Setting::get('thumbs_height');
            $thumbs_dir    = Setting::get('thumbs_dir');
            
            if(!Agent::isMobile() and Auth::check() and Helper::isAdmin(Auth::user()->id))
                $show_admin_panel = 1;
            else
                $show_admin_panel = 0;             
            
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
                'show_admin_panel',
                'thisAlbum',
                'listImages'
            );
            
            Cache::add($CacheKey, $resultData, Setting::get('cache_ttl'));
        }
        
        if($request->ajax())
            return Viewer::get('pages.images_page', $resultData);
        
        return Viewer::get('pages.images', $resultData);
        
    }
}

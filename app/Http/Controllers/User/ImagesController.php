<?php

namespace App\Http\Controllers\User;

use Illuminate\Routing\Router;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Albums;

use Auth;
use Agent;
use Roles;
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
        
        $thisAlbum = Cache::remember('albums.show.' . $url, Setting::get('cache_ttl'), function() use ($url){
            return $this->albums->where('url', $url)->first();
        });
        
        if(!$thisAlbum)
            return Viewer::get('errors.404');
        
        if (Agent::isMobile() and !Auth::check())
            $CacheKey = $url . '.Mobile' . $request->input('page');
        elseif(Agent::isMobile() and Roles::is('admin'))
            $CacheKey = $url . '.AdminMobile' . $request->input('page');
        elseif(!Agent::isMobile() and Roles::is('admin'))
            $CacheKey = $url . '.Admin' . $request->input('page');        
        else
            $CacheKey = $url . $request->input('page');
        
        if (Cache::has($CacheKey)) {
            $resultData = Cache::get($CacheKey);
        } else {
        
            $thumbs_width  = Setting::get('thumbs_width');
            $thumbs_height = Setting::get('thumbs_height');
            $thumbs_dir    = Setting::get('thumbs_dir');
            
            if(!Agent::isMobile() and Roles::is('admin'))
                $show_admin_panel = 1;
            else
                $show_admin_panel = 0;             
            
            $listImages = $thisAlbum
                    ->images()
                    ->paginate(Setting::get('count_images'));

            $resultData = compact(
                'thumbs_width',
                'thumbs_height',
                'show_admin_panel',
                'thisAlbum',
                'listImages'
            );
            
            Cache::add($CacheKey, $resultData, Setting::get('cache_ttl'));
        }
        
        if($request->ajax())
            return Viewer::get('user.image.page', $resultData);
        
        return Viewer::get('user.image.index', $resultData);
        
    }
    
    public function image(Router $router) {
        
//        echo $router->input('option');
//        echo $router->input('url');
//        echo $router->input('name');
        
        $album = $this->albums->where('url', $router->input('url'))->first();
        
        if($router->input('option') == 'thumb')
            $response = $album->thumb_path();
        elseif($router->input('option') == 'mobile')
            $response = $album->mobile_path();
        else
            $response = $album->path();
            
        $response .= "/" . $router->input('name');
        
        $file = \Storage::get($response);
        return response($file, 200)->header('Content-Type', 'image/jpeg');
//        
    }
}

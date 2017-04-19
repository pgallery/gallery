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
//use Config;

class ImagesController extends Controller
{
    
    const IMG_PAGE_COUNETR = 18;


    public function getShow(Router $router)
    {
        
        if(Albums::where('url', $router->input('url'))->count() == 0)
            return Viewer::get('errors.404');
        
        $Albums = Albums::where('url', $router->input('url'))->first();
        
        if (Agent::isMobile())
            $CacheKey = sha1($router->input('url') . '.Mobile');
        else
            $CacheKey = sha1($router->input('url')); 

        if (Cache::has($CacheKey))
        {
            $images = Cache::get($CacheKey);
        }
        else
        {
            
            $listImages = $Albums->images()->paginate(self::IMG_PAGE_COUNETR);
            foreach ($listImages as $image){        
                $images[] = [
                    'id'            => $image->id,
                    'name'          => $image->name,
                    'thumbs_url'    => Helper::getFullPathThumbImage($image->id, 'url'),
                    'image_url'     => Helper::getFullPathImage($image->id, 'url'),
                    'thumbs_width'  => Setting::get('thumbs_width'),
                    'thumbs_height' => Setting::get('thumbs_height'),
                ];
            }
//            print_r($listImages);
//            Cache::add($CacheKey, $images, Setting::get('cache_ttl'));
        }

        $album_name = $Albums->name;
        $album_desc = $Albums->desc;
        $album_year = $Albums->year;
        $album_url  = $router->input('url');
        return Viewer::get('pages.images', compact('images', 'album_name', 'album_desc', 'album_year', 'album_url', 'listImages'));

    }
    
    public function getShowPage(Router $router) {
        
        \Debugbar::disable();
        
        if(Albums::where('url', $router->input('url'))->count() == 0)
            return Viewer::get('errors.404');
        
//        Config::set('app.debug', false);
//        echo Config::get('app.debug');
//        exit;
        
        $Albums = Albums::where('url', $router->input('url'))->first();
        
           
            $listImages = $Albums->images()->paginate(self::IMG_PAGE_COUNETR);
            foreach ($listImages as $image){        
                $images[] = [
                    'id'            => $image->id,
                    'name'          => $image->name,
                    'thumbs_url'    => Helper::getFullPathThumbImage($image->id, 'url'),
                    'image_url'     => Helper::getFullPathImage($image->id, 'url'),
                    'thumbs_width'  => Setting::get('thumbs_width'),
                    'thumbs_height' => Setting::get('thumbs_height'),
                ];
            }
            

        $album_url  = $router->input('url');
//        $album_url  = $router->input('url');
        return Viewer::get('pages.images_page', compact('images', 'album_url', 'listImages'));        
        
    }
    
}

<?php

namespace App\Http\Controllers\User;

use Illuminate\Routing\Router;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Albums;
use App\Models\Images;
use App\Models\Groups;

use Auth;
use Helper;
use Setting;
use Viewer;
use Cache;

class AlbumsController extends Controller
{
    public function getShow(Router $router)
    {
        
        if(Albums::where('permission', 'All')->where('images_id', '!=', '0')->count() == 0)
            return Viewer::get('errors.404'); 
        
        if(Auth::check() and Helper::isAdmin(Auth::user()->id))
            $preCacheKey = 'Admin.';
        else
            $preCacheKey = 'User.';
        
        $preCacheKey .= 'Cache.Albums.';
        $preCacheKey .= (!empty($router->input('option'))) ? $router->input('option') : "Base" ;
        $preCacheKey .= (is_numeric($router->input('id'))) ? "." . $router->input('id') : ".Null" ;

        $CacheKey = sha1($preCacheKey);
        
        if (Cache::has($CacheKey))
        {
            $albums = Cache::get($CacheKey);
        }
        else
        {
        
            $AlbumsQuery = Albums::latest('year');
            
            if(!Auth::check() or !Helper::isAdmin(Auth::user()->id))
                $AlbumsQuery = $AlbumsQuery->where('permission', 'All');

            if($router->input('option') == "byGroup" and is_numeric($router->input('id')))
                $AlbumsQuery = $AlbumsQuery->where('groups_id', $router->input('id'));
            elseif($router->input('option') == "byYear" and is_numeric($router->input('id')))
                $AlbumsQuery = $AlbumsQuery->where('year', $router->input('id'));

            $AlbumsQuery = $AlbumsQuery->where('images_id', '!=', '0');
            
            $AlbumsQuery = $AlbumsQuery->get();

            foreach ($AlbumsQuery as $album){
                $albums[] = [
                    'id'            => $album->id,
                    'name'          => $album->name,
                    'url'           => $album->url,
                    'year'          => $album->year,
                    'desc'          => $album->desc,
                    'thumbs_url'    => Helper::getFullPathThumbImage($album->images_id, 'url'),
                    'thumbs_width'  => Setting::get('thumbs_width'),
                    'thumbs_height' => Setting::get('thumbs_height'),
                    'count'         => Images::where('albums_id', $album->id)->count(),
                    'groups_name'   => $album->group->name,
                    'size'          => round(($album->imagesSumSize() / 1024 / 1024)) . " Mb",
                ];
            }
            
            Cache::add($CacheKey, $albums, Setting::get('cache_ttl'));
        }
        
        return Viewer::get('pages.albums', compact('albums'));

    }
    
    public function getNoAccess() {
        
        return Viewer::get('errors.403');
        
    }
    
}

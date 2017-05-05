<?php

namespace App\Http\Controllers\User;

use Illuminate\Routing\Router;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Albums;
use App\Models\Images;

use Auth;
use Helper;
use Setting;
use Viewer;
use Cache;

class AlbumsController extends Controller
{
    protected $albums;
    protected $images;

    public function __construct(Albums $albums, Images $images) {
        $this->albums  = $albums;
        $this->images  = $images;
    }
    
    public function getShow(Router $router) {
        
        $countAllPublicAlbums = Cache::remember('all.public.albums', Setting::get('cache_ttl'), function() {
            return $this->albums->where('permission', 'All')->where('images_id', '!=', '0')->count();
        });
        
        if($countAllPublicAlbums == 0)
            return Viewer::get('errors.404'); 
        
        if(Auth::check() and Helper::isAdmin(Auth::user()->id))
            $CacheKey = 'Admin.';
        else
            $CacheKey = 'User.';
        
        $CacheKey .= 'Cache.Albums.';
        $CacheKey .= (!empty($router->input('option'))) ? $router->input('option') : "Base" ;
        $CacheKey .= (is_numeric($router->input('id'))) ? "." . $router->input('id') : ".Null" ;
        
        if (Cache::has($CacheKey))
        {
            $resultData = Cache::get($CacheKey);
        }
        else
        {
        
            $AlbumsQuery = $this->albums->latest('year');
            
            if(!Auth::check() or !Helper::isAdmin(Auth::user()->id))
                $AlbumsQuery = $AlbumsQuery->where('permission', 'All');

            if($router->input('option') == "byGroup" and is_numeric($router->input('id')))
                $AlbumsQuery = $AlbumsQuery->where('groups_id', $router->input('id'));
            elseif($router->input('option') == "byYear" and is_numeric($router->input('id')))
                $AlbumsQuery = $AlbumsQuery->where('year', $router->input('id'));

            $AlbumsQuery = $AlbumsQuery->where('images_id', '!=', '0');
            
            $albums = $AlbumsQuery->get();
            
            $thumbs_width  = Setting::get('thumbs_width');
            $thumbs_height = Setting::get('thumbs_height');
            $thumbs_dir    = Setting::get('thumbs_dir');
            
            $resultData = compact(
                'albums', 
                'thumbs_width', 
                'thumbs_height', 
                'thumbs_dir'
            );
            
            Cache::add($CacheKey, $resultData, Setting::get('cache_ttl'));
        }
        
        return Viewer::get('pages.albums', $resultData);

    }
    
    public function getNoAccess() {
        
        return Viewer::get('errors.403');
        
    }
    
}

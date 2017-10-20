<?php

namespace App\Http\Controllers\User;

use Illuminate\Routing\Router;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Categories;
use App\Models\Albums;
use App\Models\Tags;
use App\Models\Images;

use Auth;
use Roles;
use Setting;
use Viewer;
use Cache;

class AlbumsController extends Controller
{
    protected $categories;    
    protected $albums;
    protected $tags;    
    protected $images;
    
    public function __construct(Categories $categories, Albums $albums, Tags $tags, Images $images) {
        $this->categories = $categories;
        $this->albums     = $albums;
        $this->tags       = $tags;
        $this->images     = $images;       
    }
    
    public function getShow(Router $router) {
                
        if(Roles::is('admin')) {
        
            $countShowAlbums = Cache::remember('all.showadmin.albums', Setting::get('cache_ttl'), function() {
                return $this->albums->where('images_id', '!=', '0')->count();
            });
            
            $CacheKey = 'Admin.';
            
        } else {
            
            $countShowAlbums = Cache::remember('all.public.albums', Setting::get('cache_ttl'), function() {
                return $this->albums->where('permission', 'All')->where('images_id', '!=', '0')->count();
            });
            
            $CacheKey = 'User.';
        }
        
        if($countShowAlbums == 0)
            return Viewer::get('errors.404');         
        
        $CacheKey .= 'Cache.Albums.';
        $CacheKey .= (!empty($router->input('option'))) ? $router->input('option') : "Base" ;
        $CacheKey .= ($router->input('url')) ? "." . $router->input('url') : ".Null" ;
        
        if (Cache::has($CacheKey)) {
            
            $resultData = Cache::get($CacheKey);
            
        } else {
            
            if($router->input('option') == "tag") {
                
                $tags = $this->tags->select('id')->where('name', urldecode($router->input('url')))->first();
                
                if(Roles::is('admin'))
                    $albums = $tags->albums->where('images_id', '!=', '0');
                else
                    $albums = $tags->albums->where('permission', 'All')->where('images_id', '!=', '0');
                
            } else {
                
                $albums = $this->albums->latest('year');

                if(!Roles::is('admin'))
                    $albums = $albums->where('permission', 'All');

                if($router->input('option') == "category" and $router->input('url')) {

                    $category = $this->categories->select('id')->where('name', urldecode($router->input('url')))->firstOrFail();
                    $albums   = $albums->where('categories_id', $category->id);

                } elseif($router->input('option') == "year" and $router->input('url')) {

                    $albums = $albums->where('year', $router->input('url'));

                }

                $albums = $albums->where('images_id', '!=', '0');
                $albums = $albums->get();
            
            }
            
            $thumbs_width  = Setting::get('thumbs_width');
            $thumbs_height = Setting::get('thumbs_height');
            $thumbs_dir    = Setting::get('thumbs_dir');
            
            if(Roles::is('admin'))
                $tags = $this->tags->has('albums')->get();
            else
                $tags = $this->tags->whereHas('albums', function ($query) {
                    $query->where('permission', 'All');
                })->get();
            
            $resultData = compact(
                'albums', 
                'tags', 
                'thumbs_width', 
                'thumbs_height', 
                'thumbs_dir'
            );
            
            Cache::add($CacheKey, $resultData, Setting::get('cache_ttl'));
        }
        
        return Viewer::get('user.album.index', $resultData);

    }
}

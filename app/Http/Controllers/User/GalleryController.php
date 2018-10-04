<?php

namespace App\Http\Controllers\User;

use Illuminate\Routing\Router;
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

class GalleryController extends Controller
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
    
    /*
     * Вывод галереи
     */
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
            
            if($router->input('option') == "tag" and !empty($router->input('url'))) {
                
                $tags = $this->tags->select('id')->where('name', urldecode($router->input('url')))->firstOrFail();
                $albums = $tags->albums;
                
                if(!Roles::is('admin'))
                    $albums = $albums->where('permission', 'All');
            
                $albums = $albums->where('images_id', '!=', '0');                
                
            } elseif ($router->input('option') == "category" and !empty($router->input('url'))) {
                
                $category = $this->categories->select('id')->where('name', urldecode($router->input('url')))->firstOrFail();
                $albums = $category->albums;
                
                if(!Roles::is('admin'))
                    $albums = $albums->where('permission', 'All');
            
                $albums = $albums->where('images_id', '!=', '0');
                
            } elseif ($router->input('option') == "year" and !empty($router->input('url'))){
                
                $albums = $this->albums->orderBy('year', 'DESC')->orderBy('created_at', 'DESC');
                $albums = $albums->where('year', $router->input('url'));
                
                if(!Roles::is('admin'))
                    $albums = $albums->where('permission', 'All');
            
                $albums = $albums->where('images_id', '!=', '0');                
                $albums = $albums->get();
                
            } else {
                
                $albums = $this->albums->orderBy('year', 'DESC')->orderBy('created_at', 'DESC');
                
                if(!Roles::is('admin'))
                    $albums = $albums->where('permission', 'All');
            
                $albums = $albums->where('images_id', '!=', '0');
                $albums = $albums->get();
                
            }

            $thumbs_width  = Setting::get('thumbs_width');
            $thumbs_height = Setting::get('thumbs_height');
            $thumbs_dir    = Setting::get('thumbs_dir');
            
            if(Roles::is('admin'))
                $tags = $this->tags->whereHas('albums', function ($query) {
                    $query->where('images_id', '!=', '0');
                })->orderBy('name')->get();
            else
                $tags = $this->tags->whereHas('albums', function ($query) {
                    $query->where('permission', 'All')->where('images_id', '!=', '0');
                })->orderBy('name')->get();
            
            $resultData = compact(
                'albums', 
                'tags', 
                'thumbs_width', 
                'thumbs_height', 
                'thumbs_dir'
            );
            
            Cache::add($CacheKey, $resultData, Setting::get('cache_ttl'));
        }
        
        return Viewer::get('user.gallery.index', $resultData);

    }
}

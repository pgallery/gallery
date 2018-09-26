<?php

namespace App\Facades;

use App\Models\User;
use App\Models\Categories;
use App\Models\Albums;
use App\Models\Tags;
use App\Models\Images;

use Auth;
use Cache;
use Setting;
use Roles;
use Route;

class ViewerFacade
{
    protected $router;
    
    public function __construct() {
        $this->router = Route::current();
    }

    public function get($page, $data = false) {
        
        if(Roles::is('admin'))
            $CacheKey = 'Admin.';
        else
            $CacheKey = 'User.';
        
        $CacheKey .= 'Cache.App.Helpers.Viewer';
                
        if (Cache::has($CacheKey)) {
            
            $static = Cache::get($CacheKey);
            
        } else {
            
            if(Roles::is('admin'))
                $year_list = Albums::select('year')->orderBy('year', 'DESC')->groupBy('year')->get();
            else
                $year_list = Albums::select('year')->where('permission', 'All')->orderBy('year', 'DESC')->groupBy('year')->get();            
            
            $static = [
                'categories_count' => Categories::All()->count(),
                'categories'       => Categories::orderBy('name')->get(),
                'year_list'        => $year_list, 
                'gallery_name'     => Setting::get('gallery_name'),
            ];
            
            if(Roles::is(['admin', 'moderator'])) {

                $CountTrashedUsers      = User::onlyTrashed()->count();
                $CountTrashedCategories = Categories::onlyTrashed()->count();
                $CountTrashedAlbums     = Albums::onlyTrashed()->count();
                $CountTrashedImages     = Images::onlyTrashed()->count();
            
                $TrashedMenu = [
                    'summary_trashed'       => $CountTrashedUsers + $CountTrashedCategories + $CountTrashedAlbums + $CountTrashedImages,
                    'users_trashed'         => $CountTrashedUsers,
                    'categories_trashed'    => $CountTrashedCategories,
                    'albums_trashed'        => $CountTrashedAlbums,
                    'images_trashed'        => $CountTrashedImages,
                ];                
                
                $static = array_merge($TrashedMenu, $static); 
                
            }     
            
            Cache::add($CacheKey, $static, Setting::get('cache_ttl'));
        }

        if(!$data)
            $result = $static;
        else
            $result = array_merge($data, $static);
        
        return view('default/' . $page, $result);
        
    }

    
    public function getTitle() {
        
        $return = Setting::get('gallery_name');
        
        if(Route::is('gallery-show')) {

            $album = Albums::select('name')->where('url', $this->router->parameter("url"))->firstOrFail();
            
            $return .= ": " . $album->name;
            
        }elseif (Route::is('album-showBy') and $this->router->parameter("option") == 'category') {
            
            $return .= ": " . urldecode($this->router->parameter("url"));
        
        }elseif (Route::is('album-showBy') and $this->router->parameter("option") == 'tag') {
            
            $return .= ": " . urldecode($this->router->parameter("url"));
        
        }elseif (Route::is('album-showBy') and $this->router->parameter("option") == 'year') {
            
            $return .= ": " . $this->router->parameter("url");
        
        }
        
        return $return;
    }
    
    public function getDescription() {
        
        $return = Setting::get('gallery_name');
        
        if(Route::is('gallery-show')) {

            $album = Albums::select('desc')->where('url', $this->router->parameter("url"))->firstOrFail();
            
            $return .= ": " . $album->desc;
            
        }elseif (Route::is('album-showBy') and $this->router->parameter("option") == 'category') {
            
            $return .= ": " . urldecode($this->router->parameter("url"));
            
        }elseif (Route::is('album-showBy') and $this->router->parameter("option") == 'tag') {
            
            $return .= ": " . urldecode($this->router->parameter("url"));
        
        }elseif (Route::is('album-showBy') and $this->router->parameter("option") == 'year') {
            
            $return .= ": " . $this->router->parameter("url");
        
        }else {
            
            $return .= ": " . Setting::get('gallery_description');
            
        }
        
        return $return;        
    }
    
    public function getKeywords() {
        
        $return = '';
        
        if(Route::is('gallery-show')) {

            $album = Albums::where('url', $this->router->parameter("url"))->firstOrFail();
            
            foreach ($album->tags as $tag) {
                $return .= $tag->name . ", ";
            }
            
        }else {
            
            $tags = Cache::remember('getKeywords.tags', Setting::get('cache_ttl'), function() {
                return Tags::whereHas('albums', function ($query) {
                    $query->where('permission', 'All')->where('images_id', '!=', '0');
                })->get();
            });
            
            foreach ($tags as $tag) {
                $return .= $tag->name . ", ";
            }
                       
        }
        
        return rtrim(rtrim($return), ",");        
    }    
}
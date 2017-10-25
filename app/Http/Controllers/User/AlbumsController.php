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

use Carbon\Carbon;

class AlbumsController extends Controller
{
    protected $albums;

    public function __construct(Albums $albums) {
        $this->albums  = $albums;
    }
    
    /*
     * Вывод фотографий альбома
     */
    public function getPage(Router $router, Request $request) {
        
        $url = $router->input('url');
        
        $thisAlbum = Cache::remember('albums.show.' . $url, Setting::get('cache_ttl'), function() use ($url){
            return $this->albums->where('url', $url)->first();
        });
        
        if(!$thisAlbum)
            return Viewer::get('errors.404');

        if($thisAlbum->permission == 'Pass' and !Roles::is('admin') and !$request->session()->has("password_album_$thisAlbum->id"))
        {
            if($request->session()->get("password_album_$thisAlbum->id")['access'] != 'yes'
                or $request->session()->get("password_album_$thisAlbum->id")['key'] != md5($request->ip() . $request->header('User-Agent'))
            )
            return Viewer::get('user.album.password', ['url' => $url]);
        }

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
            return Viewer::get('user.album.page', $resultData);
        
        return Viewer::get('user.album.index', $resultData);
        
    }
    
    public function postAuth(Router $router, Request $request) {
        
        if($this->albums->where('password', $request->input('password'))->exists()){
            $album = $this->albums->select('id')->where('password', $request->input('password'))->first();
            session([
                "password_album_$album->id" => [
                    'access'        => 'yes',
                    'autorized_at'  => Carbon::now(),
                    'key'           => md5($request->ip() . $request->header('User-Agent')),
                ]
            ]);
        }
        
        return back();
    }
}

<?php

namespace App\Http\Controllers\User;

use Illuminate\Routing\Router;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Albums;
use App\Models\Images;

use Roles;
use Storage;
use Image;
use Setting;

class ImagesController extends Controller
{
    protected $albums;
    protected $images;

    public function __construct(Albums $albums, Images $images) {
        
        $this->albums = $albums;
        $this->images = $images;
        
    }
    
    /*
     * Вывод фотографии
     */
    public function getImage(Router $router, Request $request) {
        
        $album = $this->albums->where('url', $router->input('url'))->firstOrFail();
        
        if($album->permission == 'Pass' and !Roles::is('admin') and !$request->session()->has("password_album_$album->id"))
        {
            if($request->session()->get("password_album_$album->id")['access'] != 'yes'
                or $request->session()->get("password_album_$album->id")['key'] != md5($request->ip() . $request->header('User-Agent'))
            )
            return redirect()->route('gallery-show', ['url' => $router->input('url')]);
        }
        
        if($router->input('option') == 'thumb') {
            
            $path = $album->thumb_path();
            
        } elseif($router->input('option') == 'mobile') {
            
            $path = $album->mobile_path();
            
        } else {
            
            $path = $album->path();
            
        }
        
        $path .= "/" . $router->input('name');
        
        if($router->input('option') == 'thumb') {
            
            $img = Image::cache(function($image) use ($path) {
                return $image->make(Storage::get($path));
            }, Setting::get('cache_ttl'));
            
        } else {
            
            $img = Storage::get($response);
            
        }
        
        return response($img, 200)->header('Content-Type', 'image/jpeg');

    }
}

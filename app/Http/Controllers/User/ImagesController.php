<?php

namespace App\Http\Controllers\User;

use Illuminate\Routing\Router;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Albums;

use Roles;

class ImagesController extends Controller
{
    protected $albums;

    public function __construct(Albums $albums) {
        $this->albums  = $albums;
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
            
            $response = $album->thumb_path();
            
        } elseif($router->input('option') == 'mobile') {
            
            $response = $album->mobile_path();
            
        } else {
            
            $response = $album->path();
            
        }
        
        $response .= "/" . $router->input('name');
        
        $file = \Storage::get($response);
        return response($file, 200)->header('Content-Type', 'image/jpeg');

    }
}

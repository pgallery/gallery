<?php

namespace App\Http\Controllers\User;

use Illuminate\Routing\Router;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Albums;

class ImagesController extends Controller
{
    protected $albums;

    public function __construct(Albums $albums) {
        $this->albums  = $albums;
    }
    
    /*
     * Вывод фотографии
     */
    public function getImage(Router $router) {
        
        $album = $this->albums->where('url', $router->input('url'))->firstOrFail();
        
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

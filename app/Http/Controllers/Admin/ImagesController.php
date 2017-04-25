<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Routing\Router;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Albums;
use App\Models\Images;

use Image;
use File;
use Auth;
use Helper;
use Setting;
use Cache;
use BuildImage;

class ImagesController extends Controller
{
    
    public function postCreateImage(Request $request) {
        
        $album = Albums::find($request->album_id);
               
        $upload_path = Helper::getUploadPath($request->album_id);
        
        if(!File::isDirectory($upload_path))
            File::makeDirectory($upload_path, 0755, true);
        
        foreach ($request->file() as $file) {
            foreach ($file as $f) {
                
                $upload_file_name = $f->getClientOriginalName();

                if (!File::exists($upload_path . "/" . $upload_file_name))
                {
                    // Загружаем оригинал
                    Image::make($f)->save($upload_path . "/" . $upload_file_name);

                    Images::create([
                        'name'          => $f->getClientOriginalName(),
                        'albums_id'     => $request->album_id,
                        'users_id'      => Auth::user()->id,
                        'is_rebuild'    => 1,
                    ]);
                }
            }
        }
        
        // Проверяем альбом на наличие миниатюры
        if($album->images_id == 0)
        {
            
            $Images = Images::where('albums_id', $album->id)->first();
            Albums::find($album->id)->update(['images_id' => $Images->id]);
            
        }
        
        Cache::flush();
        
        return back();
        
    }    
    
    public function postRename(Request $request) {
        
        $oldName = Images::find($request->input('id'));
        
        $mobile_image = Helper::getFullPathMobileImage($request->input('id'));
        $thumb_image  = Helper::getFullPathThumbImage($request->input('id'));
        $upload_dir = Helper::getUploadPath($oldName->albums_id);
        
        if (File::exists($mobile_image))
            File::delete($mobile_image);
        
        if (File::exists($thumb_image))
            File::delete($thumb_image);        
        
        File::move($upload_dir . "/" . $oldName->name, $upload_dir . "/" . $request->input('newName'));
        Images::where('id', $request->input('id'))->update([
            'name'          => $request->input('newName'),
            'is_rebuild'    => 1,
        ]);
    
        Cache::flush();
        
        return back();
        
    }
    
    public function getRebuild(Router $router) {
        
        BuildImage::run($router->input('id'));
        
        return back();
        
    }
    
    public function getRotate(Router $router) {
        
        $file = Helper::getFullPathImage($router->input('id'));
        
        if($router->input('option') == 'left')
            $rotate = 90;
        elseif($router->input('option') == 'top')
            $rotate = 180;
        else
            $rotate = -90;
        
        Image::make($file)
            ->rotate($rotate)
            ->save($file);
        
        BuildImage::run($router->input('id'));
        
        return back();
        
    }
    
    public function deleteImage(Router $router) {
        
        $album_id = Images::find($router->input('id'))->album->id;
        
        Images::destroy($router->input('id'));
        
        if(Albums::where('id', $album_id)->where('images_id', $router->input('id'))->count() == 1);
        {
            
            if(Images::where('albums_id', $album_id)->count() == 0)
            {
                Albums::where('id', $album_id)->update(['images_id' => 0]);
            }
            else
            {
                $Images = Images::where('albums_id', $album_id)->first();
                Albums::find($album_id)->update(['images_id' => $Images->id]);
            }
        }
        
        Cache::flush();
        
        return back();
       
    }
    
    public function putInstallImage(Router $router) {
        
        $album_id = Images::find($router->input('id'))->album->id;
        Albums::find($album_id)->update(['images_id' => $router->input('id')]);
        
        return back();
        
    }
    
}

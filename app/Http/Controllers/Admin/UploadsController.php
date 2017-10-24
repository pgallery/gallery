<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Routing\Router;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Albums;
use App\Models\Images;

use Image;
use File;
use Storage;
use Auth;
use Setting;
use Transliterate;
use Cache;
use BuildImage;
use Validator;

use App\Jobs\BuildImagesJob;

class UploadsController extends Controller
{
    protected $albums;
    protected $images;

    public function __construct(Albums $albums, Images $images) {
        
        $this->middleware('g2fa');
        
        $this->albums  = $albums;
        $this->images  = $images;
    }
    
    /*
     * Загрузка изображений
     */
    public function postUploads(Router $router, Request $request) {
        
        if($router->input('option') == 'dropzone') {
        
//            Storage::append('pgallery.log', 'start dropzone');
            if($this->createImage($request->file('file'), $request->input('album_id')))
                return \Response::json([
                    'error' => false,
                    'code'  => 200
                ], 200);
            else
                return \Response::json([
                    'error'   => true,
                    'message' => 'Server error while uploading',
                    'code'    => 500
                ], 500);
            
        }else{
            
            foreach ($request->file() as $file) {
                foreach ($file as $f) {
                    $this->createImage($f, $request->input('album_id'));
                }
            }
            
            return back();
        }
       
    }
    
    private function createImage($upload_file, $album_id) {
        
        $rule = [
            'image' => 'image|mimes:jpeg,jpg,jpe,png,gif,bmp|required'
        ];

        $messages = [
            'image'    => 'должно быть изображением.',
            'mimes'    => 'расширение должно быть jpeg, jpg, jpe, png, gif, bmp.',
            'required' => 'поле обязательно.',
        ];        
        
        if(!Validator::make(['image' => $upload_file], $rule, $messages))
                return false;
        
        $album = $this->albums->find($album_id);
        
        $upload_path        = $album->path();
        $upload_file_name   = Transliterate::get($upload_file->getClientOriginalName());
        
        Storage::makeDirectory($upload_path);
//        
//        if(!File::isDirectory($upload_path))
//            File::makeDirectory($upload_path, 0755, true);
        
//        Image::make($upload_file)->save($upload_path . "/" . $upload_file_name);
        
//$file = $request->file('image');
//$name = $file->getClientOriginalName();
//$path = public_path("images/$name");
//
//Storage::put($path, File::get($file->getRealPath()));        
        
        $img = Image::make($upload_file->getRealPath());
        $img->encode();
        
        Storage::put($upload_path . "/" . $upload_file_name, (string) $img);
        
//        Storage::put($upload_path . "/" . $upload_file_name, File::get($upload_file));
        
        if($this->images->where('albums_id', $album->id)->where('name', $upload_file_name)->first()) {
            
            $image = $this->images
                    ->where('albums_id', $album->id)
                    ->where('name', $upload_file_name)
                    ->update([
                        'is_rebuild' => 1,
                    ]);
            
        }else{
            
            $image             = new Images();
            $image->name       = $upload_file_name;
            $image->albums_id  = $album->id;
            $image->users_id   = Auth::user()->id;
            $image->is_rebuild = 1;
            $image->save();
            
        }
        
        if(Setting::get('use_queue') == 'yes')
            BuildImagesJob::dispatch($image->id)->onQueue('BuildImage');  
        
        // Проверяем альбом на наличие миниатюры
        if($album->images_id == 0) {
            $image = $this->images->where('albums_id', $album->id)->first();
            $this->albums
                ->find($album->id)
                ->update([
                    'images_id' => $image->id,
                ]);
        }
        
        Cache::flush();        
        
        return true;
    }
}

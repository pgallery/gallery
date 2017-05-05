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
use Validator;

class ImagesController extends Controller
{
    protected $albums;
    protected $images;

    public function __construct(Albums $albums, Images $images) {
        $this->albums  = $albums;
        $this->images  = $images;
    }    
    
    /*
     * Загрузка (создание) изображений
     */
    public function postCreateImage(Request $request) {
        
        $rule = [
            'image' => 'image|mimes:jpeg,jpg,jpe,png,gif,bmp|required'
        ];

        $messages = [
            'image'    => 'должно быть изображением.',
            'mimes'    => 'расширение должно быть jpeg, jpg, jpe, png, gif, bmp.',
            'required' => 'поле обязательно.',
        ];

        
        $album = $this->albums->find($request->album_id);
        
        $upload_path = Helper::getUploadPath($request->album_id);
        
        if(!File::isDirectory($upload_path))
            File::makeDirectory($upload_path, 0755, true);
        
        foreach ($request->file() as $file) {
            foreach ($file as $f) {

            $validator = Validator::make(['image' => $f], $rule, $messages);

                if(!$validator->fails()) {

                    $upload_file_name = $f->getClientOriginalName();

                    if (!File::exists($upload_path . "/" . $upload_file_name))
                    {
                        // Загружаем оригинал
                        Image::make($f)->save($upload_path . "/" . $upload_file_name);

                        $this->images->create([
                            'name'          => $f->getClientOriginalName(),
                            'albums_id'     => $request->album_id,
                            'users_id'      => Auth::user()->id,
                            'is_rebuild'    => 1,
                        ]);
                    }

                }
            }
        }
        
        // Проверяем альбом на наличие миниатюры
        if($album->images_id == 0)
        {
            
            $Images = $this->images->where('albums_id', $album->id)->first();
            $this->albums->find($album->id)->update(['images_id' => $Images->id]);
            
        }
        
        Cache::flush();
        
        return back();
        
    }    
    
    /*
     * Переименовывание изображения
     */
    public function postRename(Request $request) {
        
        $oldName = $this->images->find($request->input('id'));
        
        $mobile_image = Helper::getFullPathMobileImage($request->input('id'));
        $thumb_image  = Helper::getFullPathThumbImage($request->input('id'));
        $upload_dir   = Helper::getUploadPath($oldName->albums_id);
        
        if (File::exists($mobile_image))
            File::delete($mobile_image);
        
        if (File::exists($thumb_image))
            File::delete($thumb_image);        
        
        File::move($upload_dir . "/" . $oldName->name, $upload_dir . "/" . $request->input('newName'));
        $this->images->where('id', $request->input('id'))->update([
            'name'          => $request->input('newName'),
            'is_rebuild'    => 1,
        ]);
    
        Cache::flush();
        
        return back();
        
    }
    
    /*
     * Пересоздание миниатюр выбранного изображения
     */
    public function getRebuild(Router $router) {
        
        BuildImage::run($router->input('id'));
        
        return back();
        
    }
    
    /*
     * Разворот выбранного изображения
     */
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
    
    /*
     * Удаление выбранного изображения
     */
    public function deleteImage(Router $router) {
               
        $this->images->deleteCheckAlbumPreview($router->input('id'));
        
        return back();
       
    }
    
    /*
     * Установка выбиранного изображения как миниатюру альбома
     */
    public function putInstallImage(Router $router) {
        
        $album_id = $this->images->find($router->input('id'))->album->id;
        $this->albums->find($album_id)->update(['images_id' => $router->input('id')]);
        
        \Cache::flush();
        
        return back();
        
    }
    
}

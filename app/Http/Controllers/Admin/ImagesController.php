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

use App\Jobs\BuildImagesJob;

class ImagesController extends Controller
{
    protected $albums;
    protected $images;

    public function __construct(Albums $albums, Images $images) {
        
        $this->middleware('g2fa');
        
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
                    
                    if($request->input('replace') and File::exists($upload_path . "/" . $upload_file_name))
                        File::delete($upload_path . "/" . $upload_file_name);
                    
                    if (!File::exists($upload_path . "/" . $upload_file_name)) {
                        // Загружаем оригинал
                        Image::make($f)->save($upload_path . "/" . $upload_file_name);
                        
                        if($request->input('replace') 
                            and 
                            $this->images
                                ->where('albums_id', $request->album_id)
                                ->where('name', $f->getClientOriginalName())
                                ->first()
                        )
                            $image = $this->images
                                ->where('albums_id', $request->album_id)
                                ->where('name', $f->getClientOriginalName())
                                ->first();
                            
                        else
                        
                            $image             = new Images();
                            $image->name       = $f->getClientOriginalName();
                            $image->albums_id  = $request->album_id;
                            $image->users_id   = Auth::user()->id;
                            $image->is_rebuild = 1;
                            $image->save();
                        
                        if(Setting::get('use_queue') == 'yes')
                            BuildImagesJob::dispatch($image->id)->onQueue('BuildImage');

                    }
                }
            }
        }
        
        // Проверяем альбом на наличие миниатюры
        if($album->images_id == 0)
            $this->albums
                ->find($album->id)
                ->update([
                    'images_id' => $this->images->where('albums_id', $album->id)->first()->id
                ]);
        
        Cache::flush();
        
        return back();
        
    }    
    
    /*
     * Переименовывание изображения
     */
    public function putRename(Request $request) {
        
        $oldName = $this->images->find($request->input('id'));
        
        $mobile_image = Helper::getFullPathMobileImage($request->input('id'));
        $thumb_image  = Helper::getFullPathThumbImage($request->input('id'));
        $upload_dir   = Helper::getUploadPath($oldName->albums_id);
        
        if(File::move($upload_dir . "/" . $oldName->name, $upload_dir . "/" . $request->input('newName'))) {
            
            if (File::exists($mobile_image))
                File::delete($mobile_image);

            if (File::exists($thumb_image))
                File::delete($thumb_image);            
            
            $this->images->where('id', $request->input('id'))->update([
                'name'          => $request->input('newName'),
                'is_rebuild'    => 1,
            ]);
            
            if(Setting::get('use_queue') == 'yes')
                BuildImagesJob::dispatch($request->input('id'))->onQueue('BuildImage');
            
            
            Cache::flush();
        }
        
        return back();
        
    }
    
    /*
     * Пересоздание миниатюр выбранного изображения
     */
    public function getRebuild(Router $router) {

        $image = $this->images->find($router->input('id'));
        $image->is_rebuild = 1;
        $image->save();

        if(Setting::get('use_queue') == 'yes')
            BuildImagesJob::dispatch($router->input('id'))->onQueue('BuildImage');
        
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

        $image = $this->images->find($router->input('id'));
        $image->is_rebuild = 1;
        $image->save();

        if(Setting::get('use_queue') == 'yes')
            BuildImagesJob::dispatch($router->input('id'))->onQueue('BuildImage');
        
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
        
        Cache::flush();
        
        return back();
        
    }
    
    /*
     * Изменение владельца изображения
     */
    public function putChangeOwnerImage(Request $request) {
        
        $image = $this->images->find($request->input('id'));
        $image->users_id = $request->input('ChangeOwnerNew');
        $image->save();
        
        Cache::flush();
        
        return back();
        
    }
    
    /*
     * Перемещение изображения в другой альбом
     */
    public function putMoveToAlbum(Request $request) {
        
        $this_image     = $this->images->find($request->input('id'));
        $new_album      = $this->albums->find($request->input('MoveToAlbumNew'));
        
        $old_path       = Helper::getFullPathImage($this_image->id);
        $new_path       = Helper::getUploadPath($new_album->id) . "/" . $this_image->name;
        $delete_thumb   = Helper::getFullPathThumbImage($this_image->id);
        $delete_mobile  = Helper::getFullPathMobileImage($this_image->id);
        
        if(File::move($old_path, $new_path) and $this_image->albums_id != $request->input('MoveToAlbumNew')) {
            
            if (File::exists($delete_thumb))
                File::delete($delete_thumb);
            
            if (File::exists($delete_mobile))
                File::delete($delete_mobile);            
            
            $this->images->where('id', $this_image->id)->update([
                'albums_id'  => $new_album->id,
                'is_rebuild' => 1,
            ]);
            
            if($this_image->album->images_id == $this_image->id) {
                
                $Images = $this->images->where('albums_id', $this_image->albums_id)->first();
                $this->albums->find($this_image->albums_id)->update(['images_id' => $Images->id]);
                
            }
            
        }
        
        Cache::flush();
       
        return redirect()->route('show-album', ['id' => $new_album->id]);
        
    }    
    
}

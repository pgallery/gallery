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
use Setting;
use Transliterate;
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
     * Переименовывание изображения
     */
    public function putRename(Request $request) {
        
        $image = $this->images->find($request->input('id'));
        
        $new_name     = Transliterate::get($request->input('newName'));
        
        $mobile_image = $image->mobile_path();
        $thumb_image  = $image->thumb_path();
        $upload_dir   = $image->album->path();
        
        if(File::move($upload_dir . "/" . $image->name, $upload_dir . "/" . $new_name)) {
            
            if (File::exists($mobile_image))
                File::delete($mobile_image);

            if (File::exists($thumb_image))
                File::delete($thumb_image);            
            
            $image->update([
                'name'          => $new_name,
                'is_rebuild'    => 1,
            ]);
            
            if(Setting::get('use_queue') == 'yes') {
                BuildImagesJob::dispatch($image->id)->onQueue('BuildImage');
            }
            
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
        
        $image = $this->images->find($router->input('id'));
        
        $file = $image->path();
        
        if($router->input('option') == 'left')
            $rotate = 90;
        elseif($router->input('option') == 'top')
            $rotate = 180;
        else
            $rotate = -90;
        
        Image::make($file)
            ->rotate($rotate)
            ->save($file);
        
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
        
        $old_path       = $this_image->path();
        $new_path       = $new_album->path() . "/" . $this_image->name;
        $delete_thumb   = $this_image->thumb_path();
        $delete_mobile  = $this_image->mobile_path();
        
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

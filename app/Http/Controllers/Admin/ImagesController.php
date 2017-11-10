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
        
        $image = $this->images->findOrFail($request->input('id'));
        
        $new_name = Transliterate::get($request->input('newName'));
        
        if(Storage::has($image->album->path() . "/" . $new_name))
            return back();
        
        if(Storage::move($image->path(), $image->album->path() . "/" . $new_name)) {
            
            if(Storage::has($image->thumb_path()))
                Storage::delete($image->thumb_path());

            if(Storage::has($image->mobile_path()))
                Storage::delete($image->mobile_path());
            
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

        $image = $this->images->findOrFail($router->input('id'));
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
        
        $image = $this->images->findOrFail($router->input('id'));
        
//        $file = $image->path();
        
        if($router->input('option') == 'left')
            $rotate = 90;
        elseif($router->input('option') == 'top')
            $rotate = 180;
        else
            $rotate = -90;
        
        $rotate = Image::make(Storage::get($image->path()))
            ->rotate($rotate);
        
        Storage::put($image->path(), (string) $rotate->encode());
        
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
        
        $album_id = $this->images->findOrFail($router->input('id'))->album->id;
        $this->albums->findOrFail($album_id)->update(['images_id' => $router->input('id')]);
        
        Cache::flush();
        
        return back();
        
    }
    
    /*
     * Изменение владельца изображения
     */
    public function putChangeOwnerImage(Request $request) {
        
        $image = $this->images->findOrFail($request->input('id'));
        $image->users_id = $request->input('ChangeOwnerNew');
        $image->save();
        
        Cache::flush();
        
        return back();
        
    }
    
    /*
     * Перемещение изображения в другой альбом
     */
    public function putMoveToAlbum(Request $request) {
        
        $this_image = $this->images->findOrFail($request->input('id'));
        $new_album  = $this->albums->findOrFail($request->input('MoveToAlbumNew'));
                
        if($this_image->albums_id != $request->input('MoveToAlbumNew') and Storage::move($this_image->path(), $new_album->path() . "/" . $this_image->name)) {
            
            if(Storage::has($this_image->thumb_path()))
                Storage::delete($this_image->thumb_path());

            if(Storage::has($this_image->mobile_path()))
                Storage::delete($this_image->mobile_path());
            
            $this->images->where('id', $this_image->id)->update([
                'albums_id'  => $new_album->id,
                'is_rebuild' => 1,
            ]);
            
            if(Setting::get('use_queue') == 'yes') {
                BuildImagesJob::dispatch($this_image->id)->onQueue('BuildImage');
            }
            
            // Проверяем, не переносим ли мы миниатюру альбома
            // Если да, устанавливаем новую
            if($this_image->album->images_id == $this_image->id) {
                
                $Images = $this->images->where('albums_id', $this_image->albums_id)->first();
                $this->albums->findOrFail($this_image->albums_id)->update(['images_id' => $Images->id]);
                
            }
            // Проверяем, есть ли миниатюра у альбома приемника
            // Если нет, устанавливаем переносимую фотограцию как миниатюру
            if($new_album->images_id == 0) {
                
                $new_album->images_id = $this_image->id;
                $new_album->save();
                
            }
            
        }
        
        Cache::flush();
       
        return redirect()->route('show-album', ['id' => $new_album->id]);
        
    }    
    
}

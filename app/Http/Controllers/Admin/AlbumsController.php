<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Routing\Router;
use Illuminate\Http\Request;
use App\Http\Requests\AlbumsRequest;
use App\Http\Requests\AlbumsDirectoryRequest;
use App\Http\Requests\AlbumsEditRequest;
use App\Http\Controllers\Controller;

use App\Models\Groups;
use App\Models\Albums;
use App\Models\Images;

use Auth;
use Helper;
use Setting;
use Viewer;
use File;
use Cache;

class AlbumsController extends Controller
{
    
    // 10080 минут - 1 неделя
    const SHOWADMIN_CACHE_TTL = 10080;    
    
    protected $groups;
    protected $albums;
    protected $images;

    public function __construct(Groups $groups, Albums $albums, Images $images) {
        $this->groups  = $groups;
        $this->albums  = $albums;
        $this->images  = $images;
    }
    
    /*
     * Удаление альбома
     */
    public function deleteAlbum(Router $router) {
        
        $this->albums->deleteWithImages($router->input('id'));
        
        return redirect()->route('admin');
        
    }
    
    /*
     * Вывод формы редактирования альбома
     */
    public function getEditAlbum(Router $router) {
        
        $album  = $this->albums->find($router->input('id'));
        $groupsArray = $this->groups->pluck('name','id');

        return Viewer::get('admin.album_edit', [
            'type'          => 'edit',
            'album'         => $album,
            'groupsArray'   => $groupsArray,
        ]);
        
    }
    
    /*
     * Создание нового альбома
     */
    public function postCreateAlbum(AlbumsRequest $request) {
        
        $input              = $request->all();
        $input['url']       = ($request->input('url')) ? $request->input('url') : md5($request->input('name'));
        $input['desc']      = ($request->input('desc')) ? $request->input('desc') : $request->input('name');
        $input['users_id']  = Auth::user()->id;
        
        $this->albums->create($input); 
        
        $album_directory = Setting::get('upload_dir') . "/" . $request->input('directory');
        
        if(!File::isDirectory($album_directory))
            File::makeDirectory($album_directory,  0755, true);
        
        Cache::flush();
        
        return back();
        
    }
    
    /*
     * Вывод фотографий выбранного альбома
     */
    public function getShowAlbum(Router $router, Request $request) {
                
        $thisAlbum = $this->albums->find($router->input('id'));
        
        if($thisAlbum->imagesCount() == 0) {
            $images=[];
        } else {
            
            $thisPage = $request->has('page') ? $request->query('page') : 1;
            
            $thumbs_dir = Setting::get('thumbs_dir');
            $mobile_dir = Setting::get('mobile_upload_dir');
            $upload_dir = Setting::get('upload_dir'); 
            $type       = 'thisAlbum';
            $album_id   = $thisAlbum->id;
            
            $listImages = Cache::remember('admin.show.albumImages.' . $thisAlbum->id . '.' . $thisPage, self::SHOWADMIN_CACHE_TTL, function() use ($thisAlbum) {                
                return $thisAlbum->images()->paginate(Setting::get('count_images'));
            });
            
        }
        
        return Viewer::get('admin.show_album', compact(
            'upload_dir',
            'mobile_dir',
            'thumbs_dir',
            'thisAlbum',
            'listImages',
            'type',
            'album_id'
        ));
        
    }
    
    /*
     * Сохранение изменений альбома
     */
    public function putSaveAlbum(Router $router, AlbumsEditRequest $request) {
        
        $input          = $request->all();
        $input['url']   = ($request->input('url')) ? $request->input('url') : md5($request->input('name'));
        $input['desc']  = ($request->input('desc')) ? $request->input('desc') : $request->input('name');

        $this->albums->find($router->input('id'))->update($input);
        
        \Cache::flush();
        
        return redirect()->route('admin');
        
    }
    
    /*
     * Синхронизация изображений альбома из локальной директории
     */
    public function getSync(Router $router){
        
        $accessType = [
            'image/jpeg',
            'image/gif',
            'image/png',
            'image/bmp',
        ];
        
        $album  = $this->albums->find($router->input('id'));        
        $images = File::Files(Helper::getUploadPath($album->id));
        
        foreach ($images as $image) {
            
            $base_filename = basename($image);
            $mimeType = File::mimeType($image);
            
            if(in_array($mimeType, $accessType)) {
                if($this->images->where('name', $base_filename)->where('albums_id', $album->id)->count() == 0) {
                        $this->images->create([
                            'name'       => $base_filename,
                            'albums_id'  => $album->id,
                            'users_id'   => Auth::user()->id,
                            'is_rebuild' => 1,
                        ]);
                }
            }
        }
        
        // Проверяем альбом на наличие миниатюры
        if($album->images_id == 0) {
            
            $Images = $this->images->where('albums_id', $album->id)->first();
            $this->albums->find($album->id)->update(['images_id' => $Images->id]);
            
        }
        
        Cache::flush();
        
        return redirect()->route('admin');
    }
    
    /*
     * Пересоздание миниатюр всех изображений выборанного альбома
     */
    public function getRebuild(Router $router) {
        
        $this->images->where('albums_id', $router->input('id'))->update([
            'is_rebuild'    => 1,
        ]);
        
        return redirect()->route('admin');
    }
    
    /*
     * Вывод формы загрузки изображений в выбранный альбом
     */
    public function getUploads(Router $router) {

        $Album = $this->albums->find($router->input('id'));
        
        return Viewer::get('admin.album_uploads', [
            'type'              => 'thisAlbum',
            'album_id'          => $Album->id,
            'album_name'        => $Album->name,
        ]);        
        
    }
    
    /*
     *  Вывод формы переименовывания директории альбома
     */
    public function getRenameDir(Router $router) {
        
        $thisAlbum = $this->albums->find($router->input('id'));
        
        return Viewer::get('admin.album_renamedir', compact(
            'thisAlbum'
        ));
        
    }
    
    /*
     * Переименование директории
     */
    public function putRenameDir(Router $router, AlbumsDirectoryRequest $request) {
        
        $album = $this->albums->find($router->input('id'));
        
        $upload_path = \Helper::getUploadPath($router->input('id'));
        $mobile_path = \Helper::getFullPathMobile($router->input('id'));
        $thumb_path  = \Helper::getFullPathThumb($router->input('id'));        

        if(File::move($upload_path, public_path() . "/" . Setting::get('upload_dir') . "/" . $request->input('directory'))) {
            if(\File::isDirectory($mobile_path))
                \File::deleteDirectory($mobile_path);

            if(\File::isDirectory($thumb_path))
                \File::deleteDirectory($thumb_path);            
            
            $this->albums->find($router->input('id'))->update($request->all());

            $this->images->where('albums_id', $router->input('id'))->update([
                'is_rebuild'    => 1,
            ]);
            
            Cache::flush();
        }
        
        return redirect()->route('admin');
    }
}

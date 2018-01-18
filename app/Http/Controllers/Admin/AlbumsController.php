<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Routing\Router;
use Illuminate\Http\Request;
use App\Http\Requests\AlbumsRequest;
use App\Http\Controllers\Controller;

use App\Models\User;
use App\Models\Categories;
use App\Models\Albums;
use App\Models\Tags;
use App\Models\Images;

use Auth;
use Setting;
use Viewer;
use Transliterate;
use File;
use Storage;
use Cache;

use App\Jobs\BuildImagesJob;

class AlbumsController extends Controller {

    // 10080 минут - 1 неделя
    const SHOWADMIN_CACHE_TTL = 10080;

    protected $users;
    protected $categories;
    protected $albums;
    protected $tags;
    protected $images;

    public function __construct(User $users, Categories $categories, Albums $albums, Tags $tags, Images $images) {
        $this->middleware('g2fa');

        $this->users      = $users;
        $this->categories = $categories;
        $this->albums     = $albums;
        $this->tags       = $tags;
        $this->images     = $images;
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

        $categoriesArray = Cache::remember('admin.show.categoriesArray', self::SHOWADMIN_CACHE_TTL, function() {
            return $this->categories->orderBy('name')->pluck('name','id');
        });

        $usersArray = Cache::remember('admin.show.usersArray', self::SHOWADMIN_CACHE_TTL, function() {
            return $this->users->pluck('name','id');
        });
        
        $album = $this->albums->findOrFail($router->input('id'));
        
        $tags = '';
        foreach ($album->tags as $tag) {
            $tags .= $tag->name . ", ";
        }
        $tags = rtrim(rtrim($tags), ',');
        
        return Viewer::get('admin.album.edit', compact([
            'album',
            'tags',
            'categoriesArray',
            'usersArray'
        ]));
    }

    /*
     * Создание нового альбома
     */
    public function postCreateAlbum(AlbumsRequest $request) {

        $album                = new Albums();
        $album->name          = $request->input('name');
        $album->url           = ($request->input('url')) ? $request->input('url') : md5($request->input('name'));
        $album->directory     = Transliterate::get($request->input('directory'));
        $album->year          = $request->input('year');
        $album->desc          = ($request->input('desc')) ? $request->input('desc') : $request->input('name');
        $album->permission    = $request->input('permission');
        
        if($request->input('permission') == 'Pass' and empty($request->input('password')))
            $album->password = rand(1000000, 9999999);
        elseif($request->input('permission') == 'Pass' and !empty($request->input('password')))
            $album->password = $request->input('password');
        else
            $album->password = null;

        $album->categories_id = $request->input('categories_id');
        $album->users_id      = Auth::user()->id;
        $album->save();

        if(!empty($request->input('tags')))
            $album->tags()->sync($this->_tags_to_array($request->input('tags')));
        
        Storage::makeDirectory($album->path());
        
        Cache::flush();

        return redirect()->route('uploads-album', ['id' => $album->id]);
    }

    /*
     * Вывод фотографий выбранного альбома
     */
    public function getShowAlbum(Router $router, Request $request) {

        $thisAlbum = $this->albums->findOrFail($router->input('id'));

        if ($thisAlbum->imagesCount() == 0) {
            $images = [];
        } else {

            $thisPage = $request->has('page') ? $request->query('page') : 1;

            $thumbs_dir  = Setting::get('thumbs_dir');
            $mobile_dir  = Setting::get('mobile_upload_dir');
            $upload_dir  = Setting::get('upload_dir');
            $type        = 'thisAlbum';
            $album_id    = $thisAlbum->id;
            
            $usersArray = Cache::remember('admin.show.usersArray', self::SHOWADMIN_CACHE_TTL, function() {
                return $this->users->pluck('name','id');
            });
            $albumsArray = Cache::remember('admin.show.albumsArray', self::SHOWADMIN_CACHE_TTL, function() {
                return $this->albums->pluck('name', 'id');
            });
            $listImages = Cache::remember('admin.show.albumImages.' . $thisAlbum->id . '.' . $thisPage, self::SHOWADMIN_CACHE_TTL, function() use ($thisAlbum) {
                return $thisAlbum->images()->paginate(Setting::get('count_images'));
            });
        }

        return Viewer::get('admin.album.index', compact(
            'upload_dir', 
            'mobile_dir', 
            'thumbs_dir', 
            'thisAlbum', 
            'listImages', 
            'type', 
            'album_id', 
            'usersArray', 
            'albumsArray'
        ));
    }

    /*
     * Сохранение изменений альбома
     */
    public function putSaveAlbum(AlbumsRequest $request) {
        
        $album = $this->albums->findOrFail($request->input('id'));
        
        // Если изменена директория, переносим файлы
        if($album->directory != $request->input('directory'))
            $this->_rename_dir($request->input('id'), $request->input('directory'));
        
        // Если изменен владелец, меняем его
        if($album->users_id != $request->input('users_id'))
            $this->_change_owner($request->input('id'), $request->input('users_id'), $request->input('owner_recursion'));
        
        // Если есть теги, создаем их
        if(!empty($request->input('tags')))
            $album->tags()->sync($this->_tags_to_array($request->input('tags')));

        $input['name']          = $request->input('name');
        $input['year']          = $request->input('year');
        $input['categories_id'] = $request->input('categories_id');
        $input['permission']    = $request->input('permission');
        
        if($request->input('permission') == 'Pass' and empty($request->input('password')))
            $input['password'] = rand(1000000, 9999999);
        elseif($request->input('permission') == 'Pass' and !empty($request->input('password')))
            $input['password'] = $request->input('password');
        else
            $input['password'] = null;
        
        $input['desc']          = ($request->input('desc')) ? $request->input('desc') : $request->input('name');
        $input['url']           = ($request->input('url')) ? $request->input('url') : md5($request->input('name'));
        
        $album->update($input);

        Cache::flush();

        return redirect()->route('admin');
    }

    /*
     * Синхронизация изображений альбома из локальной директории
     */
    public function getSync(Router $router) {

        $accessType = [
            'image/jpeg',
            'image/gif',
            'image/png',
            'image/bmp',
        ];

        $album = $this->albums->findOrFail($router->input('id'));
        $images = Storage::files($album->path());
        
        foreach ($images as $image) {

            $base_filename = basename($image);
            $mimeType = Storage::getMimetype($album->path() . "/" . $base_filename);
            
            if (in_array($mimeType, $accessType)) {
                
                if (!$this->images->where('name', $base_filename)->where('albums_id', $album->id)->exists()) {
                    
                    $image              = new Images();
                    $image->name        = $base_filename;
                    $image->albums_id   = $album->id;
                    $image->users_id    = Auth::user()->id;
                    $image->is_rebuild  = 1;
                    $image->save();

                    if (Setting::get('use_queue') == 'yes')
                        BuildImagesJob::dispatch($image->id)->onQueue('BuildImage');
                    
                }
            }
        }

        // Проверяем альбом на наличие миниатюры
        if ($album->images_id == 0) {
            $thumb = $this->images->where('albums_id', $album->id)->first();
            $this->albums
                    ->findOrFail($album->id)
                    ->update([
                        'images_id' => $thumb->id,
                    ]);
        }
        
        Cache::flush();

        return redirect()->route('admin');
    }

    /*
     * Пересоздание миниатюр всех изображений выбранного альбома
     */
    public function getRebuild(Router $router) {

        $this->images->where('albums_id', $router->input('id'))->update([
            'is_rebuild' => 1,
        ]);

        if (Setting::get('use_queue') == 'yes') {
            $album = $this->albums->findOrFail($router->input('id'));
            $album_images = $album->images;

            foreach ($album_images as $image) {
                BuildImagesJob::dispatch($image->id)->onQueue('BuildImage');
            }
            
        }
        return redirect()->route('admin');
    }

    /*
     * Вывод формы загрузки изображений в выбранный альбом
     */
    public function getUploads(Router $router) {

        $album = $this->albums->findOrFail($router->input('id'));

        return Viewer::get('admin.album.uploads', [
            'type'       => 'thisAlbum',
            'album_id'   => $album->id,
            'album_name' => $album->name,
        ]);
    }

    /*
     * Переименование директории
     */
    private function _rename_dir($id, $directory) {
        
        $album = $this->albums->findOrFail($id);

        $directory   = Transliterate::get($directory);
        
        if (Storage::move($album->path(), Setting::get('upload_dir') . "/" . $directory)) {

            if(Storage::has($album->mobile_path()))
                Storage::deleteDirectory($album->mobile_path());

            if(Storage::has($album->thumb_path()))
                Storage::deleteDirectory($album->thumb_path());

            $album->update([
                'directory' => $directory,
            ]);

            $this->images->where('albums_id', $album->id)->update([
                'is_rebuild' => 1,
            ]);
            
            if (Setting::get('use_queue') == 'yes') {
                $album_images = $album->images;

                foreach ($album_images as $image) {
                    BuildImagesJob::dispatch($image->id)->onQueue('BuildImage');
                }
            }
        }
    }    
    
    /*
     * Изменение владельца альбома
     */
    private function _change_owner($id, $users_id, $recursion = false) {

        $this->albums->findOrFail($id)->update([
            'users_id' => $users_id,
        ]);

        if ($recursion == 'yes')
            $this->images->where('albums_id', $id)->update([
                'users_id' => $users_id,
            ]);

    }
    
    /*
     * Приобразуем строку тегов в массив ID тегов
     */
    private function _tags_to_array($tags){
        
        $tags = explode(",", $tags);
        foreach ($tags as $tag) {
            $tag = trim($tag);

            if(!empty($tag)) {

                $tag_attach = $this->tags->firstOrCreate([
                    'name' => $tag
                ]);

                $tags_attach[] = $tag_attach->id;

            }
        }
        
        return $tags_attach;
    }
}

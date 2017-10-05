<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Routing\Router;
use Illuminate\Http\Request;
use App\Http\Requests\AlbumsRequest;
use App\Http\Requests\AlbumsDirectoryRequest;
use App\Http\Requests\AlbumsEditRequest;
use App\Http\Controllers\Controller;

use App\Models\User;
use App\Models\Groups;
use App\Models\Albums;
use App\Models\Images;

use Auth;
use Helper;
use Setting;
use Viewer;
use File;
use Cache;

use App\Jobs\BuildImagesJob;

class AlbumsController extends Controller {

    // 10080 минут - 1 неделя
    const SHOWADMIN_CACHE_TTL = 10080;

    protected $users;
    protected $groups;
    protected $albums;
    protected $images;

    public function __construct(User $users, Groups $groups, Albums $albums, Images $images) {
        $this->middleware('g2fa');

        $this->users  = $users;
        $this->groups = $groups;
        $this->albums = $albums;
        $this->images = $images;
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

        $album = $this->albums->find($router->input('id'));
        $groupsArray = $this->groups->pluck('name', 'id');

        return Viewer::get('admin.album.edit', [
                    'type' => 'edit',
                    'album' => $album,
                    'groupsArray' => $groupsArray,
        ]);
    }

    /*
     * Создание нового альбома
     */
    public function postCreateAlbum(AlbumsRequest $request) {

        $input = $request->all();
        $input['url'] = ($request->input('url')) ? $request->input('url') : md5($request->input('name'));
        $input['desc'] = ($request->input('desc')) ? $request->input('desc') : $request->input('name');
        $input['users_id'] = Auth::user()->id;

        $this->albums->create($input);

        $album_directory = Setting::get('upload_dir') . "/" . $request->input('directory');

        if (!File::isDirectory($album_directory))
            File::makeDirectory($album_directory, 0755, true);

        Cache::flush();

        return back();
    }

    /*
     * Изменение владельца альбома
     */
    public function putChangeOwnerAlbum(Request $request) {

        $this->albums->where('id', $request->input('id'))->update([
            'users_id' => $request->input('ChangeOwnerAlbumNew'),
        ]);

        if ($request->input('ChangeOwnerAlbumRecursion'))
            $this->images->where('albums_id', $request->input('id'))->update([
                'users_id' => $request->input('ChangeOwnerAlbumNew'),
            ]);

        Cache::flush();

        return back();
    }

    /*
     * Вывод фотографий выбранного альбома
     */
    public function getShowAlbum(Router $router, Request $request) {

        $thisAlbum = $this->albums->find($router->input('id'));

        if ($thisAlbum->imagesCount() == 0) {
            $images = [];
        } else {

            $thisPage = $request->has('page') ? $request->query('page') : 1;

            $thumbs_dir  = Setting::get('thumbs_dir');
            $mobile_dir  = Setting::get('mobile_upload_dir');
            $upload_dir  = Setting::get('upload_dir');
            $type        = 'thisAlbum';
            $album_id    = $thisAlbum->id;
            $usersArray  = $this->users->pluck('name', 'id');
            $albumsArray = $this->albums->pluck('name', 'id');

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
    public function putSaveAlbum(Router $router, AlbumsEditRequest $request) {

        $input = $request->all();
        $input['url'] = ($request->input('url')) ? $request->input('url') : md5($request->input('name'));
        $input['desc'] = ($request->input('desc')) ? $request->input('desc') : $request->input('name');

        $this->albums->find($router->input('id'))->update($input);

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

        $album = $this->albums->find($router->input('id'));
        $images = File::Files(Helper::getUploadPath($album->id));

        foreach ($images as $image) {

            $base_filename = basename($image);
            $mimeType = File::mimeType($image);

            if (in_array($mimeType, $accessType)) {
                if ($this->images->where('name', $base_filename)->where('albums_id', $album->id)->count() == 0) {

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
        if ($album->images_id == 0)
            $this->albums
                    ->find($album->id)
                    ->update([
                        'images_id' => $this->images->where('albums_id', $album->id)->first()->id
                    ]);

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
            $album = $this->albums->find($router->input('id'));
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

        $album = $this->albums->find($router->input('id'));

        return Viewer::get('admin.album.uploads', [
            'type'       => 'thisAlbum',
            'album_id'   => $album->id,
            'album_name' => $album->name,
        ]);
    }

    /*
     *  Вывод формы переименовывания директории альбома
     */
    public function getRenameDir(Router $router) {

        $thisAlbum = $this->albums->find($router->input('id'));

        return Viewer::get('admin.album.renamedir', compact(
            'thisAlbum'
        ));
    }

    /*
     * Переименование директории
     */
    public function putRenameDir(Router $router, AlbumsDirectoryRequest $request) {
        
        $album = $this->albums->find($router->input('id'));

        $upload_path = Helper::getUploadPath($album->id);
        $mobile_path = Helper::getFullPathMobile($album->id);
        $thumb_path  = Helper::getFullPathThumb($album->id);

        if (File::move($upload_path, public_path() . "/" . Setting::get('upload_dir') . "/" . $request->input('directory'))) {

            if (\File::isDirectory($mobile_path))
                \File::deleteDirectory($mobile_path);

            if (\File::isDirectory($thumb_path))
                \File::deleteDirectory($thumb_path);

            $album->update([
                'directory' => $request->input('directory'),
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
            Cache::flush();
        }

        return redirect()->route('admin');
    }
}

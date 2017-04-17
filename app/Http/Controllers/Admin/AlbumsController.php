<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Routing\Router;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\User;
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
    public function deleteAlbum(Router $router) {
        
        $CacheKey = sha1(Albums::find($router->input('id'))->url);
        
        if (Cache::has($CacheKey))
                Cache::forget($CacheKey);        
        
        Albums::destroy($router->input('id'));
        Images::where('albums_id', $router->input('id'))->delete();
        
        return redirect()->route('admin');
        
    }
    
    public function getEditAlbum(Router $router) {
        
        $album = Albums::find($router->input('id'));
        
        $groups = Groups::all();
        
        return Viewer::get('admin.album_edit', [
            'type'              => 'edit',
            'albumId'           => $album->id,
            'albumName'         => $album->name,
            'albumUrl'          => $album->url,
            'albumDir'          => $album->directory,
            'albumDesc'         => $album->desc,
            'albumGroup'        => $album->groups_id,
            'albumYear'         => $album->year,
            'albumPermission'   => $album->permission,
            'groups'            => $groups,
        ]);
        
    }
    
    public function postCreateAlbum(Request $request) {
        
        if(empty($request->albumUrl))
            $getAlbumUrl = md5($request->albumName);
        else
            $getAlbumUrl = $request->albumUrl;
        
        Albums::create([
            'name'          => $request->albumName, 
            'url'           => $getAlbumUrl, 
            'directory'     => $request->albumDir, 
            'images_id'     => '0', 
            'year'          => $request->albumYear, 
            'desc'          => $request->albumDesc, 
            'permission'    => $request->albumPermission, 
            'groups_id'     => $request->albumGroup, 
            'users_id'      => Auth::user()->id,
        ]);
        
        if(!File::isDirectory(Setting::get('upload_dir') . "/" . $request->albumDir))
            File::makeDirectory(Setting::get('upload_dir') . "/" . $request->albumDir, 0755, true);
        
        return redirect()->route('create');
        
    }

    public function getShowAlbum(Router $router) {
        
        $album = Albums::find($router->input('id'));
        
        if($image->size != NULL)
            $file_size = round($image->size / 1024) . " Kb";
        else
            $file_size = "0 Kb";
        
        foreach ($album->images as $image){
            $images[] = [
                'id'            => $image->id,
                'name'          => $image->name,
                'size'          => $file_size,
                'owner'         => User::find($image->users_id)->name,
                'thumbs_url'    => Helper::getFullPathThumbImage($image->id, 'url'),
                'image_url'     => Helper::getFullPathImage($image->id, 'url'),
                'thumbs_width'  => Setting::get('thumbs_width'),
                'thumbs_height' => Setting::get('thumbs_height'),
            ];
        }
        
        return Viewer::get('admin.show_album', [
            'album_name'    => $album->name,
            'album_preview' => $album->images_id,
            'images'        => $images, 
        ]);
        
    }
    
    public function putSaveEditAlbum(Router $router, Request $request) {
        
        $CacheKey = sha1(Albums::find($router->input('id'))->url);
        
        if (Cache::has($CacheKey))
                Cache::forget($CacheKey);
        
        Albums::where('id', $router->input('id'))->update([
            'name'          => $request->albumName,
            'url'           => $request->albumUrl,
            'desc'          => $request->albumDesc,
            'groups_id'     => $request->albumGroup,
            'year'          => $request->albumYear,
            'permission'    => $request->albumPermission,
        ]);
        
        return redirect()->route('admin');
        
    }    
    
    public function getSync(Router $router){
        
        $album = Albums::find($router->input('id'));
        
        $CacheKey = sha1($album->url);
        
        if (Cache::has($CacheKey))
                Cache::forget($CacheKey);        
        
        $files = File::allFiles(Helper::getUploadPath($router->input('id')));
        foreach ($files as $file)
        {
            $base_filename = basename($file);
            
            if(Images::where('name', $base_filename)->where('albums_id', $router->input('id'))->count() == 0)
            {
                    Images::create([
                        'name'      => $base_filename,
                        'albums_id' => $router->input('id'),
                        'users_id'  => Auth::user()->id,
                    ]);
            }
        }
        
        // Проверяем альбом на наличие миниатюры
        if($album->images_id == 0)
        {
            
            $Images = Images::where('albums_id', $album->id)->first();
            Albums::find($album->id)->update(['images_id' => $Images->id]);
            
        }
        
        return redirect()->route('admin');
    }
    
    public function getUploads(Router $router) {

        $Album = Albums::find($router->input('id'));
        
        return Viewer::get('admin.album_uploads', [
            'type'              => 'thisAlbum',
            'album_id'          => $Album->id,
            'album_name'        => $Album->name,
        ]);        
        
    }
}

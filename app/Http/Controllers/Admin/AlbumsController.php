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
    
    // 10080 минут - 1 неделя
    const SHOWADMIN_CACHE_TTL = 10080;    
    
    public function deleteAlbum(Router $router) {
        
        Albums::destroy($router->input('id'));
        Images::where('albums_id', $router->input('id'))->delete();
        
        $CacheKey = sha1(Albums::find($router->input('id'))->url);
        
        if (Cache::has($CacheKey))
                Cache::forget($CacheKey);        
        
        Cache::forget(sha1('admin.show.albums'));
        
        return redirect()->route('admin');
        
    }
    
    public function getEditAlbum(Router $router) {
        
        $album = Albums::find($router->input('id'));

        $groups = Cache::remember(sha1('admin.show.groups'), self::SHOWADMIN_CACHE_TTL, function() {
            return Groups::All();
        });        
        
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
        
        $getAlbumUrl = ($request->albumUrl) ? $request->albumUrl : md5($request->albumName) ;
        $albumDesc = ($request->albumDesc) ? $request->albumDesc : $request->albumName;
        
        Albums::create([
            'name'          => $request->albumName, 
            'url'           => $getAlbumUrl, 
            'directory'     => $request->albumDir, 
            'images_id'     => '0', 
            'year'          => $request->albumYear, 
            'desc'          => $albumDesc, 
            'permission'    => $request->albumPermission, 
            'groups_id'     => $request->albumGroup, 
            'users_id'      => Auth::user()->id,
        ]);
        
        if(!File::isDirectory(Setting::get('upload_dir') . "/" . $request->albumDir))
            File::makeDirectory(Setting::get('upload_dir') . "/" . $request->albumDir, 0755, true);
        
        Cache::forget(sha1('admin.show.albums'));
        
        return back()->withInput();
        
    }

    public function getShowAlbum(Router $router, Request $request) {
                
        $thisAlbum = Albums::find($router->input('id'));
        
        if($thisAlbum->imagesCount() == 0)
        {
            $images=[];
        }
        else
        {
            
            $thisPage = $request->has('page') ? $request->query('page') : 1;
            
            $thumbs_dir = Setting::get('thumbs_dir');
            $mobile_dir = Setting::get('mobile_upload_dir');
            $upload_dir = Setting::get('upload_dir'); 
            
            $listImages = Cache::remember(sha1('admin.show.albumImages.' . $thisAlbum->id . '.' . $thisPage), self::SHOWADMIN_CACHE_TTL, function() use ($thisAlbum) {                
                return $thisAlbum->images()->paginate(Setting::get('count_images'));
            });
            
        }
        
        return Viewer::get('admin.show_album', compact(
            'upload_dir',
            'mobile_dir',
            'thumbs_dir',
            'thisAlbum',
            'listImages'
        ));
        
    }
    
    public function putSaveAlbum(Router $router, Request $request) {
        
        $getAlbumUrl = ($request->albumUrl) ? $request->albumUrl : md5($request->albumName) ;
        $albumDesc = ($request->albumDesc) ? $request->albumDesc : $request->albumName;
        
        Albums::where('id', $router->input('id'))->update([
            'name'          => $request->albumName,
            'url'           => $getAlbumUrl,
            'desc'          => $albumDesc,
            'groups_id'     => $request->albumGroup,
            'year'          => $request->albumYear,
            'permission'    => $request->albumPermission,
        ]);
        
        if (Cache::has(Albums::find($router->input('id'))->url))
                Cache::forget(Albums::find($router->input('id'))->url);        
        
        Cache::forget(sha1('admin.show.albums'));
        
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
            
//    // Build the input for validation
//    $fileArray = array('image' => $file);
//
//    // Tell the validator that this file should be an image
//    $rules = array(
//      'image' => 'mimes:jpeg,jpg,png,gif|required|max:10000' // max 10000kb
//    );
//
//    // Now pass the input and rules into the validator
//    $validator = Validator::make($fileArray, $rules);
//
//    // Check to see if validation fails or passes
//    if ($validator->fails())
//    {
//          // Redirect or return json to frontend with a helpful message to inform the user 
//          // that the provided file was not an adequate type
//          return response()->json(['error' => $validator->errors()->getMessages()], 400);
//    } else
//    {
//        // Store the File Now
//        // read image from temporary file
//        Image::make($file)->resize(300, 200)->save('foo.jpg');
//    };            
            
            $base_filename = basename($file);
            
            if(Images::where('name', $base_filename)->where('albums_id', $router->input('id'))->count() == 0)
            {
                    Images::create([
                        'name'       => $base_filename,
                        'albums_id'  => $router->input('id'),
                        'users_id'   => Auth::user()->id,
                        'is_rebuild' => 1,
                    ]);
            }
        }
        
        // Проверяем альбом на наличие миниатюры
        if($album->images_id == 0)
        {
            
            $Images = Images::where('albums_id', $album->id)->first();
            Albums::find($album->id)->update(['images_id' => $Images->id]);
            
        }
        
        Cache::forget(sha1('admin.show.albums'));
        
        return redirect()->route('admin');
    }
    
    public function getRebuild(Router $router) {
        
        Images::where('albums_id', $router->input('id'))->update([
            'is_rebuild'    => 1,
        ]);
        
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

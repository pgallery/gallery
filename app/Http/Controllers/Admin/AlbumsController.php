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
        
        return back()->withInput();
        
    }

    public function getShowAlbum(Router $router) {
        
        $album = Albums::find($router->input('id'));
        
        if($album->imagesCount() == 0)
        {
            $images=[];
        }
        else
        {
            $listImages = $album->images()->paginate(Setting::get('count_images'));
            foreach ($listImages as $image){

                if($image->size > 0)
                    $file_size = round($image->size / 1024) . " Kb";
                else
                    $file_size = "0 Kb";

                $images[] = [
                    'id'            => $image->id,
                    'name'          => $image->name,
                    'size'          => $file_size,
                    'height'        => $image->height,
                    'width'         => $image->width,                    
                    'owner'         => User::find($image->users_id)->name,
                    'image_url'     => Helper::getFullPathImage($image->id, 'url'),
                    'thumbs_url'    => Helper::getFullPathThumbImage($image->id, 'url'),
                    'mobile_url'    => Helper::getFullPathMobileImage($image->id, 'url'),
                    'thumbs_width'  => Setting::get('thumbs_width'),
                    'thumbs_height' => Setting::get('thumbs_height'),
                ];
            }
        }
        
        return Viewer::get('admin.show_album', [
            'album_name'    => $album->name,
            'album_preview' => $album->images_id,
            'images'        => $images, 
            'album_images'  => $listImages,
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
        
        return redirect()->route('admin');
    }
    
    public function getRebuild(Router $router) {
        
//        $album = Albums::find($router->input('id'));
        
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

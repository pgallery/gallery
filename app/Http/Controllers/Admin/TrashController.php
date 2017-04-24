<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Groups;
use App\Models\Albums;
use App\Models\Images;

use File;
use Helper;
use Setting;
use Viewer;
use Cache;

class TrashController extends Controller
{
    public function deleteTrash() {
        
        $images = Images::onlyTrashed()->get();

        foreach ($images as $image){
            
            $full_image = Helper::getFullPathImage($image['id']);
            $mobile_image = Helper::getFullPathMobileImage($image['id']);
            $thumb_image = Helper::getFullPathThumbImage($image['id']);
            
            if (File::exists($full_image))
                File::delete($full_image);
            
            if (File::exists($mobile_image))
                File::delete($mobile_image);
            
            if (File::exists($thumb_image))
                File::delete($thumb_image); 
            
            $this->destroyImage($image->id);
            
        }
        
        $albums = Albums::onlyTrashed()->get();
        foreach ($albums as $album){

            $CacheKey = sha1($album->url);
            
            if (Cache::has($CacheKey))
                Cache::forget($CacheKey);
                
            $upload_path = public_path() . "/" . Setting::get('upload_dir') . "/" . $album->directory;
            $mobile_path = public_path() . "/" . Setting::get('mobile_upload_dir') . "/" . $album->directory;
            $thumb_path = public_path() . "/" . Setting::get('thumbs_dir') . "/" . $album->directory;
            
            if(File::isDirectory($upload_path))
                File::deleteDirectory($upload_path);
            
            if(File::isDirectory($mobile_path))
                File::deleteDirectory($mobile_path);

            if(File::isDirectory($thumb_path))
                File::deleteDirectory($thumb_path);            
                      
            $this->destroyAlbum($album->id);
        }   
        
        $groups = Groups::onlyTrashed()->get();
        foreach ($groups as $group){
            $this->destroyGroup($group->id);
        }        

        return back()->withInput();
        
    }
    
    public function getGroups() {
        
        $groups = Groups::onlyTrashed()->get();
        
        return Viewer::get('admin.show_trashed', [
            'groups'    => $groups,
        ]);        
        
    }    
    
    private function destroyGroup($id){
        
        $group = Groups::withTrashed()->findOrFail($id);
        $group->forceDelete();
        
    }
    
    private function destroyAlbum($id){
        
        $album = Albums::withTrashed()->findOrFail($id);
        $album->forceDelete();
        
    }
    
    private function destroyImage($id){
        
        $image = Images::withTrashed()->findOrFail($id);
        $image->forceDelete();
        
    }

    
}

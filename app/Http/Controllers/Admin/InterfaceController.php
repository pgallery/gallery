<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\User;
use App\Models\Groups;
use App\Models\Albums;
use App\Models\Roles;

use Auth;
use Helper;
use Viewer;

class InterfaceController extends Controller
{
    public function getPage(){
               
        if(Groups::All()->count() == 0)
            $groups=[];
        else
            $groups = Groups::All();
        
        if(Albums::All()->count() == 0)
        {
            $albums=[];
        }
        else
        {
            $AllAlbums = Albums::All();

            foreach ($AllAlbums as $album){
                if($album->images_id != 0)
                    $thumbs_url = Helper::getFullPathThumbImage($album->images_id, 'url');
                else
                    $thumbs_url = "";
                
                $albums[] = [
                    'id'            => $album->id,
                    'name'          => $album->name,
                    'url'           => env('APP_URL') . "/gallery-" . $album->url,
                    'thumbs_url'    => $thumbs_url,
                    'year'          => $album->year,
                    'permission'    => $album->permission,
                    'owner'         => User::find($album->users_id)->name,
                    'count'         => $album->imagesCount(),
                    'summary_size'  => round(($album->imagesSumSize() / 1024 / 1024)) . " Mb",
                    'groups_name'   => $album->group->name,
                ];
            }
        }
        
        return Viewer::get('admin.show', [
            'albums'    => $albums, 
            'groups'    => $groups,
        ]);
        
    }
    
    public function getCreateForm(){
        
        $groups = Groups::all();  
        $albums = Albums::all();  
        
        return Viewer::get('admin.create', [
            'albums'     => $albums, 
            'groups'     => $groups,
        ]);
        
    }
}

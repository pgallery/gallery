<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Routing\Router;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\User;
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
    protected $user;    
    protected $groups;
    protected $albums;
    protected $images;

    public function __construct(User $user, Groups $groups, Albums $albums, Images $images) {
        
        $this->middleware('g2fa');
        
        $this->user    = $user;        
        $this->groups  = $groups;
        $this->albums  = $albums;
        $this->images  = $images;
    }
    
    public function deleteTrash() {
        
        $images = $this->images->onlyTrashed()->get();

        foreach ($images as $image){
            $this->images->destroyImage($image->id);
        }
        
        $albums = $this->albums->onlyTrashed()->get();
        
        foreach ($albums as $album){
            $this->albums->destroyAlbum($album->id);
        }
        
        $groups = $this->groups->onlyTrashed()->get();
        
        foreach ($groups as $group){
            $this->groups->destroyGroup($group->id);
        }

        $users = $this->user->onlyTrashed()->get();
        
        foreach ($users as $user){
            $this->user->destroyUser($user->id);
        }
        
        Cache::flush();
        
        return back();
        
    }
    
    public function getOptionPage(Router $router) {
        
        $groups = $this->groups->onlyTrashed()->get();
        
        return Viewer::get('admin.show_trashed', [
            'groups'    => $groups,
        ]);        
        
    }
    
}

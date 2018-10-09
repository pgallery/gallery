<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Routing\Router;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\User;
use App\Models\Categories;
use App\Models\Albums;
use App\Models\Images;

use File;
use Setting;
use Viewer;
use Cache;

class TrashController extends Controller
{
    protected $user;    
    protected $categories;
    protected $albums;
    protected $images;

    public function __construct(User $user, Categories $categories, Albums $albums, Images $images) {
        
        $this->middleware('g2fa');
        
        $this->user       = $user;
        $this->categories = $categories;
        $this->albums     = $albums;
        $this->images     = $images;
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
        
        $categories = $this->categories->onlyTrashed()->get();
        
        foreach ($categories as $category){
            $this->categories->destroyCategorie($category->id);
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
        
        return Viewer::get('admin.trashed.index', [
            'groups'    => $groups,
        ]);        
        
    }
    
}

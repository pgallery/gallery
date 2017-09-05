<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\User;
use App\Models\Groups;
use App\Models\Albums;
use App\Models\Roles;

use Auth;
use Helper;
use Setting;
use Viewer;
use Image;
use Cache;

class InterfaceController extends Controller
{
    // 10080 минут - 1 неделя
    const SHOWADMIN_CACHE_TTL = 10080;
    
    /*
     * Отображение общего списка групп/альбомов на странице администратора
     */
    public function getPage(){
        
        $thumbs_dir = Setting::get('thumbs_dir');
        
        $groups = Cache::remember('admin.show.groups', self::SHOWADMIN_CACHE_TTL, function() {
            return Groups::All();
        });
        
        $groupsArray = Cache::remember('admin.show.groupsArray', self::SHOWADMIN_CACHE_TTL, function() {
            return Groups::orderBy('name')->pluck('name','id');
        });
        
        $albums = Cache::remember('admin.show.albums', self::SHOWADMIN_CACHE_TTL, function() {
            return Albums::all();
        });   
        
        $usersArray = User::pluck('name','id');
        
        return Viewer::get('admin.show', compact(
            'groupsArray',
            'thumbs_dir',
            'albums', 
            'groups',
            'usersArray'
        ));
        
    }
    
    /*
     * Отображение общей формы создания групп/альбомов/изображений на странице администратора
     */    
    public function getCreateForm(){
        
        $groupsArray = Cache::remember('admin.show.groupsArray', self::SHOWADMIN_CACHE_TTL, function() {
            return Groups::orderBy('name')->pluck('name','id');
        });
        
        $albumsArray = Cache::remember('admin.show.albumsArray', self::SHOWADMIN_CACHE_TTL, function() {
            return Albums::orderBy('name')->pluck('name','id');
        });
        
        return Viewer::get('admin.create', compact(
            'albumsArray', 
            'groupsArray'
        ));
        
    }
}

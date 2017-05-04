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
use Setting;
use Viewer;
use Image;
use Cache;

class InterfaceController extends Controller
{
    // 10080 минут - 1 неделя
    const SHOWADMIN_CACHE_TTL = 10080;
//    const SHOWALBUMS_COUNTER_PAGE = 10;
    
    /*
     * Отображение общего списка групп/альбомов на странице администратора
     */
    public function getPage(){
        
//        if(Groups::count() == 0) {
//            $groups = [];
//            $groupsArray = [];
//        } else {
            $groups = Cache::remember(sha1('admin.show.groups'), self::SHOWADMIN_CACHE_TTL, function() {
                return Groups::All();
            });
            $groupsArray = Cache::remember(sha1('admin.show.groupsArray'), self::SHOWADMIN_CACHE_TTL, function() {
                return Groups::orderBy('name')->pluck('name','id');
            });
//        }
        
        $thumbs_dir = Setting::get('thumbs_dir');
        
        if(Albums::count() == 0) {
            $albums=[];
        } else {
            
            $albums = Cache::remember(sha1('admin.show.albums'), self::SHOWADMIN_CACHE_TTL, function() {
                return Albums::all();
            });
            
        }
        
        $resultData = compact(
            'groupsArray',
            'thumbs_dir',
            'albums', 
            'groups'
        );        
        
        return Viewer::get('admin.show', $resultData);
        
    }
    
    /*
     * Отображение общей формы создания групп/альбомов/изображений на странице администратора
     */    
    public function getCreateForm(){
        
        $groupsArray = Cache::remember(sha1('admin.show.groupsArray'), self::SHOWADMIN_CACHE_TTL, function() {
            return Groups::orderBy('name')->pluck('name','id');
        });
        
        $albumsArray = Cache::remember(sha1('admin.show.albumsArray'), self::SHOWADMIN_CACHE_TTL, function() {
            return Albums::orderBy('name')->pluck('name','id');
        });
        
        return Viewer::get('admin.create', compact(
            'albumsArray', 
            'groupsArray'
        ));
        
    }
}

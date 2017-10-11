<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Routing\Router;
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
    
    protected $users;
    protected $groups;
    protected $albums;
    
    public function __construct(User $users, Groups $groups, Albums $albums) {
        $this->middleware('g2fa');
    
        $this->users  = $users;
        $this->groups = $groups;
        $this->albums = $albums;
    }
    
    /*
     * Отображение общего списка групп/альбомов на странице администратора
     */
    public function getPage(Router $router){
        
        $groups = Cache::remember('admin.show.groups', self::SHOWADMIN_CACHE_TTL, function() {
            return $this->groups->All();
        });
        
        $groupsArray = Cache::remember('admin.show.groupsArray', self::SHOWADMIN_CACHE_TTL, function() {
            return $this->groups->orderBy('name')->pluck('name','id');
        });
        
        if($router->input('option') == 'byGroup' and is_numeric($router->input('id'))) {
            $byGroupId = $router->input('id');
            $albums = Cache::remember('admin.show.albums.byGroup.' . $byGroupId, self::SHOWADMIN_CACHE_TTL, function() use ($byGroupId) {
                return $this->albums->where('groups_id', $byGroupId)->get();
            });
        } else {
            $albums = Cache::remember('admin.show.albums', self::SHOWADMIN_CACHE_TTL, function() {
                return $this->albums->all();
            });
        }
        
        $usersArray = Cache::remember('admin.show.usersArray', self::SHOWADMIN_CACHE_TTL, function() {
            return $this->users->pluck('name','id');
        });
        
        $thumbs_dir = Setting::get('thumbs_dir');
        
        return Viewer::get('admin.page.index', compact(
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
        
        return Viewer::get('admin.page.create', compact(
            'albumsArray', 
            'groupsArray'
        ));
    }
}

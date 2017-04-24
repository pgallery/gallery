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
    const SHOWALBUMS_COUNTER_PAGE = 10;

    public function getPage(){
        
        if(Groups::All()->count() == 0)
            $groups=[];
        else
            $groups = Cache::remember(sha1('admin.show.groups'), self::SHOWADMIN_CACHE_TTL, function() {
                return Groups::All();
            });
        
        if(Albums::All()->count() == 0)
        {
            $albums=[];
        }
        else
        {

            $CacheKey = sha1('admin.show.albums');        

            if (Cache::has($CacheKey))
            {
                $albums = Cache::get($CacheKey);
            }
            else
            {            
            
                $AllAlbums = Albums::all();

                foreach ($AllAlbums as $album){

                    if($album->images_id != 0)
                        $thumbs_url = Helper::getFullPathThumbImage($album->images_id, 'url');
                    else
                        $thumbs_url = "";

                    $albums[] = [
                        'id'            => $album->id,
                        'name'          => $album->name,
                        'url'           => $album->url,
                        'thumbs_url'    => $thumbs_url,
                        'year'          => $album->year,
                        'permission'    => $album->permission,
                        'owner'         => $album->owner()->name,
                        'count'         => $album->imagesCount(),
                        'summary_size'  => $album->imagesSumSize(),
                        'groups_name'   => $album->group->name,
                    ];
                }
            
                Cache::add($CacheKey, $albums, Setting::get('cache_ttl'));
            }
        }
        
        return Viewer::get('admin.show', [
            'albums'    => $albums, 
            'groups'    => $groups,
        ]);
        
    }
    
    public function getCreateForm(){
        
//        $groups = Cache::remember(sha1('admin.show.groups'), self::SHOWADMIN_CACHE_TTL, function() {
//            return Groups::All();
//        });
        $groups = Groups::pluck('name','id');
        $albums = Albums::all();  
        
        return Viewer::get('admin.create', [
            'albums'     => $albums, 
            'groups'     => $groups,
        ]);
        
    }
}

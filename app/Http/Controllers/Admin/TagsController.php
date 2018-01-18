<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Routing\Router;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Tags;
use App\Models\Albums;

use Viewer;
use Cache;

class TagsController extends Controller
{
    // 10080 минут - 1 неделя
    const SHOWADMIN_CACHE_TTL = 10080;    
    
    protected $albums;
    protected $tags;

    public function __construct(Albums $albums, Tags $tags) {
        $this->middleware('g2fa');

        $this->albums = $albums;
        $this->tags   = $tags;
    }    
    
    /*
     * Список тегов
     */
    public function getTags() {
    
        $tags = Cache::remember('admin.show.tags', self::SHOWADMIN_CACHE_TTL, function() {
            return $this->tags->all();
        });
        
        return Viewer::get('admin.tag.index', compact(
            'tags'
        ));        
    }
    
    /*
     * Переименовывание изображения
     */
    public function putRename(Request $request) {
        
        $tag = $this->tags->findOrFail($request->input('id'));
        
        $tag->update(['name' => $request->input('newName')]);
        
        Cache::flush();
        
        return back();
    }
    
    /*
     * Удаление выбранного тега
     */
    public function deleteTag(Router $router) {
        
        $this->tags->destroy($router->input('id'));
        
        Cache::flush();
        
        return back();
    }
}

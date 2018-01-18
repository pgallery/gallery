<?php

namespace App\Http\Controllers\Admin;

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
    
    public function getTags() {
    
        $tags = Cache::remember('admin.show.tags', self::SHOWADMIN_CACHE_TTL, function() {
            return $this->tags->all();
        });
        
        return Viewer::get('admin.tag.index', compact(
            'tags'
        ));        
    }
}

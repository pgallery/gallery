<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Setting;
use Cache;

class Menu extends Model
{
    
    use SoftDeletes;       

    protected $table = 'menu';
    
    protected $dates = ['deleted_at'];
    
    protected $fillable = [
        'name'
    ];
    
    public function tags() {
        return $this->belongsToMany('App\Models\Tags', 'tags_menu');
    }

    public function tagsRelation() {
        return Cache::remember('menu.tags_' . $this->id . '_cache', Setting::get('cache_ttl'), function () {
            return $this->tags()->orderBy('name')->get();
        });
    }
    
}

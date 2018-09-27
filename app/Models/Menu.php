<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Menu extends Model
{
    
    use SoftDeletes;    
    
    protected $table = 'menu';
    
    protected $dates = ['deleted_at'];
    
    protected $fillable = [
        'name'
    ];
    
    public function tags() {
        return $this->belongsToMany('App\Models\Tags', 'tags_menu')->orderBy('name');
    }    
}

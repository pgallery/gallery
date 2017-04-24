<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

//use Cache;

class Images extends Model
{
    
    use SoftDeletes;
    
    protected $dates = ['deleted_at'];
    
    protected $fillable = [
        'name', 'size', 'albums_id', 'users_id', 'is_rebuild', 'is_thumbs', 'is_modile'
    ];
    
    public function album()
    {
        return $this->hasOne('App\Models\Albums', 'id', 'albums_id')->withTrashed();
    }
    
    public function owner()
    {
        return \Cache::remember(sha1('owner_' . $this->users_id . '_cache'), 100, function(){
            return $this->hasOne('App\User', 'id', 'users_id')->select('name')->first();           
        });
    }
    
}

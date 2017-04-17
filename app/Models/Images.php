<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Images extends Model
{
    
    use SoftDeletes;
    
    protected $dates = ['deleted_at'];
    
    protected $fillable = [
        'name', 'size', 'albums_id', 'users_id', 'is_thumbs', 'is_modile'
    ];
    
    public function album()
    {
        return $this->hasOne('App\Models\Albums', 'id', 'albums_id')->withTrashed();
    }
    
}

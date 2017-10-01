<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Archives extends Model
{
    use SoftDeletes;    
    
    protected $dates = ['deleted_at'];    
    
    protected $fillable = [
        'name', 'users_id'
    ];    
}

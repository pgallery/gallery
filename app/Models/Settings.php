<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    
    protected $primaryKey = null;
    
    public $incrementing = false;
    
    protected $fillable = [
        'set_name', 'set_value', 'set_variations', 'set_desc', 'set_group', 'set_tooltip', 'set_type',
    ];
}

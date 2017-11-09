<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Archives extends Model
{
    use SoftDeletes;    
    
    protected $dates = ['deleted_at'];    
    
    protected $fillable = [
        'name', 'size', 'users_id', 'albums_id'
    ];
    
    public static function destroyWithZipper($id) {
        $archive = self::find($id);
        
        if (\File::exists($archive->name))
            \File::delete($archive->name);
        
        self::destroy($id);
    }
}

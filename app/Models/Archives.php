<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Zipper;

class Archives extends Model
{
    use SoftDeletes;    
    
    protected $dates = ['deleted_at'];    
    
    protected $fillable = [
        'name', 'size', 'users_id', 'albums_id'
    ];
    
    public function createWithZipper($album_url) {
        
        $album = Albums::where('url', $album_url)->first();
        
        $archive_name = public_path() . "/" . \Setting::get('archive_dir') . "/" . $album->directory . ".zip";
        
        if (!\File::exists($archive_name)) {
            
            $files = glob(\Helper::getUploadPath($album->id) . '/*');
            Zipper::make($archive_name)->add($files)->close();

            $archive = self::create([
                'name'      => $archive_name,
                'size'      => \File::size($archive_name),
                'users_id'  => \Illuminate\Support\Facades\Auth::user()->id,
                'albums_id' => $album->id,
            ]);
        } else {
            
            $archive = self::where('name', $archive_name)->first();
            
        }
        
        return $archive;
    }
    
    public static function destroyWithZipper($id) {
        $archive = self::find($id);
        
        if (\File::exists($archive->name))
            \File::delete($archive->name);
        
        self::destroy($id);
    }
}

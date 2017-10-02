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
    
    public function createWithZipper($album_id) {
        $album = Albums::find($album_id);
        
        $archive_name = public_path() . "/" . \Setting::get('archive_dir') . "/" . $album->id . ".zip";
        
        if (!\File::exists($archive_name)) {
            $files = glob(\Helper::getUploadPath($album->id) . '/*');
            Zipper::make($archive_name)->add($files)->close();

            self::create([
                'name'      => $archive_name,
                'size'      => \File::size($archive_name),
                'users_id'  => \Illuminate\Support\Facades\Auth::user()->id,
                'albums_id' => $album_id,
            ]);
        }
        return $archive_name;
    }
    
    public function destroyWithZipper($id) {
        $archive = self::find($id);
//        echo $archive->name;
        
        if (\File::exists($archive->name))
            \File::delete($archive->name);
        
        self::destroy($id);
    }
}

<?php

namespace App\Http\Controllers\User;

use Illuminate\Routing\Router;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Archives;
use App\Models\Albums;

use Auth;
use Setting;
use Carbon\Carbon;
use Storage;
use File;
use Zipper;

use App\Jobs\ArchivesCleanJob;

class ArchivesController extends Controller
{
    protected $archives;
    protected $albums;
    
    public function __construct(Archives $archives, Albums $albums) {
        
        $this->archives = $archives;
        $this->albums   = $albums;
        
    }
    
    /*
     * Архивация альбома
     */
    public function getZip(Router $router) {
        
        $archive = $this->_create($router->input('url'));
        
        if(Setting::get('use_queue') == 'yes')
            ArchivesCleanJob::dispatch($archive->id)
                ->onQueue('ArchivesClean')
                ->delay(Carbon::now()->addHours(Setting::get('archive_save')));
        
        return response()->download($archive->name);

    }
    
    private function _create($url){
        
        $album = $this->albums->where('url', $url)->firstOrFail();
        
        $archive_name = storage_path(Setting::get('archive_dir') . "/" . $album->directory . ".zip");
        $tmp_dir = storage_path(Setting::get('archive_dir') . "/__" . sha1(Carbon::now() . $album->directory . "_tmp"));
        
        if (!File::exists($archive_name)) {
            
            if (!File::isDirectory($tmp_dir))
                File::makeDirectory($tmp_dir, 0755, true);

            $zipper = Zipper::make($archive_name);

            foreach ($album->images as $image) {

                $file = Storage::get($image->path());
                File::put($tmp_dir . '/' . $image->name, $file);

                $zipper->add($tmp_dir . '/' . $image->name);
            }
       
            $zipper->close();
            
            $archive = $this->archives->create([
                'name'      => $archive_name,
                'size'      => File::size($archive_name),
                'users_id'  => Auth::user()->id,
                'albums_id' => $album->id,
            ]);
            
            File::deleteDirectory($tmp_dir);
            
        } else {
            
            $archive = $this->archives->where('name', $archive_name)->firstOrFail();
            
        }
        
        return $archive;
        
    }
}

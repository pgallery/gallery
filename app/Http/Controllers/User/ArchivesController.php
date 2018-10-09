<?php

namespace App\Http\Controllers\User;

use Illuminate\Routing\Router;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Archives;
use App\Models\Albums;

use Auth;
use Roles;
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
        
        $album = $this->albums->where('url', $router->input('url'))->firstOrFail();
        
        if($album->permission == 'Pass' and !Roles::is('admin') and !$request->session()->has("password_album_$album->id"))
        {
            if($request->session()->get("password_album_$album->id")['access'] != 'yes'
                or $request->session()->get("password_album_$album->id")['key'] != md5($request->ip() . $request->header('User-Agent'))
            )
            return redirect()->route('gallery-show', ['url' => $router->input('url')]);
        }        
        
        $archive = $this->_create($album->id);
        
        if(Setting::get('use_queue') == 'yes')
            ArchivesCleanJob::dispatch($archive->id)
                ->onQueue('ArchivesClean')
                ->delay(Carbon::now()->addHours(Setting::get('archive_save')));
        
        return response()->download($archive->name);

    }
    
    private function _create($id){
        
        $album = $this->albums->findOrFail($id);
        $archive_name = storage_path(Setting::get('archive_dir') . "/" . $album->directory . ".zip");
        
        if (!File::exists($archive_name)) {
            
            if(env('DISK_DRIVER') == 'local') {
            
                $files = glob($album->path() . '/*');
                Zipper::make($archive_name)->add($files)->close();
                
            }else{
                
                $tmp_dir = storage_path(Setting::get('archive_dir') . "/__" . sha1(Carbon::now() . $album->directory . "_tmp"));
                
                if (!File::isDirectory($tmp_dir))
                    File::makeDirectory($tmp_dir, 0755, true);

                $zipper = Zipper::make($archive_name);

                foreach ($album->images as $image) {
                    $file = Storage::get($image->path());
                    File::put($tmp_dir . '/' . $image->name, $file);

                    $zipper->add($tmp_dir . '/' . $image->name);
                }

                $zipper->close();

                File::deleteDirectory($tmp_dir);
            }

            $archive = $this->archives->create([
                'name'      => $archive_name,
                'size'      => File::size($archive_name),
                'users_id'  => Auth::user()->id,
                'albums_id' => $album->id,
            ]);     
            
        } else {
            
            $archive = $this->archives->where('name', $archive_name)->firstOrFail();
            
        }
        
        return $archive;
        
    }
}

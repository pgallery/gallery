<?php

namespace App\Http\Controllers\User;

use Illuminate\Routing\Router;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Archives;

use Setting;
use Carbon\Carbon;

use App\Jobs\ArchivesCleanJob;

class ArchivesController extends Controller
{
    protected $archives;
    
    public function __construct(Archives $archives) {
        $this->archives = $archives;        
    }
    
    /*
     * Архивация альбома
     */
    public function getZip(Router $router) {
        
        $archive = $this->archives->createWithZipper($router->input('url'));
        
        if(Setting::get('use_queue') == 'yes')
            ArchivesCleanJob::dispatch($archive->id)
                ->onQueue('ArchivesClean')
                ->delay(Carbon::now()->addHours(Setting::get('archive_save')));
        
        return response()->download($archive->name);

    }  
}

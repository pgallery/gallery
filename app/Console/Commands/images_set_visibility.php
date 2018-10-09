<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\Albums;
use App\Models\Images;
use Storage;
use File;
use Setting;

use App\Jobs\BuildImagesJob;

class images_set_visibility extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'images:set_visibility {--force} {--clear}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Импорт миниатюр публичных альбомов в web директорию';
    
    protected $albums;
    protected $images;
    protected $successful = 0;
    protected $fail       = 0;
    protected $skip       = 0;
    
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Albums $albums, Images $images)
    {
        parent::__construct();
        
        $this->albums = $albums;
        $this->images = $images;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if(Setting::get('saveinpublic_thumbs') != 'yes' and Setting::get('saveinpublic_mobiles') != 'yes'){
            echo "\n\033[0;31mFunctionality is disabled in the settings\033[0m\n\n";
            exit;
        }
        
        if($this->option('clear')) {
            if(File::isDirectory(public_path("images")))
                File::deleteDirectory(public_path("images"));

            echo "\n\033[0;32mAll files from the web directory are deleted\033[0m\n\n";
            exit;
        }
        
        $albums = $this->albums->where('permission', 'All')->get();
        
        if($this->option('force') and File::isDirectory(public_path("images")))
            File::deleteDirectory(public_path("images"));
        
        foreach ($albums as $album) {
            
            echo "\033[1m#\n";
            echo "# Album name: $album->name\n";
            echo "#\033[0m\n";
            
            if(Setting::get('saveinpublic_thumbs') == 'yes') {
                echo "\n# Save file type: \033[1mThumbs\033[0m\n";
                $this->_saveInPublic($album->id, 'thumb');
            }
            
            if(Setting::get('saveinpublic_mobiles') == 'yes') {
                echo "\n# Save file type: \033[1mMobiles\033[0m\n\n";
                $this->_saveInPublic($album->id, 'mobile');
            }
        }
        
        echo "\033[1mFinish:\033[0m\n\n";
        echo "Successful: $this->successful \n";
        echo "Skip: $this->skip \n";
        echo "Fail: $this->fail\n";

    }
    
    private function _saveInPublic($id, $type = 'thumb'){

        $album = $this->albums->findOrFail($id);
        
        $web_album_path = public_path("images/" . $type . "/" . $album->url);

        if (!File::isDirectory($web_album_path))
            File::makeDirectory($web_album_path, 0755, true);

        foreach ($album->images as $image) {

            echo "File: " . $image->name . " ";

            $web_file_path = $web_album_path . "/" . $image->name;
            $FileExists    = File::exists($web_file_path);
            $file_path     = (($type == 'thumb') ?  $image->thumb_path() : $image->mobile_path() );
            
            if(!Storage::has($file_path)) {
                echo "\033[0;31mSource file not found!\033[0m\n";

                $image = $this->images->findOrFail($image->id);
                $image->is_rebuild = 1;
                $image->save();

                if(Setting::get('use_queue') == 'yes')
                    BuildImagesJob::dispatch($image->id)->onQueue('BuildImage');                    

                $this->fail++;

                continue;
            }

            $img = Storage::get($file_path);

            if((!$FileExists or $this->option('force')) and File::put($web_file_path, $img)) {

                echo "\033[0;32mSuccessful\033[0m\n";
                $this->successful++;

            } elseif($FileExists) {

                echo "\033[33mSkip\033[0m\n";
                $this->skip++;

            } else {

                echo "\033[0;31mFail\033[0m\n";
                $this->fail++;

            }

        }
        echo "\n";        
        
    }
}

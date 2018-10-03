<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\Albums;
use App\Models\Images;

use Storage;
use Transliterate;
use Cache;
use Setting;

use App\Jobs\BuildImagesJob;

class ImageRename extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'image:rename_to_transliterate {--album-id=} {--all}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Переименовывание файла под настройки Транслитирации';
    
    protected $albums;
    protected $images;

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
        
        if(empty($this->option('all')) and empty($this->option('album-id'))) {
            exit;
        }
        
        if($this->option('all')) {
            
            foreach ($this->images->all() as $image) {
                
                if($image->name != Transliterate::get($image->name)){
                    
                    echo $image->name . " => " . Transliterate::get($image->name) . "\n";
                    
                    $new_name = Transliterate::get($image->name);
                    
                    if(Storage::move($image->path(), $image->album->path() . "/" . $new_name)) {

                        if(Storage::has($image->thumb_path()))
                            Storage::delete($image->thumb_path());

                        if(Storage::has($image->mobile_path()))
                            Storage::delete($image->mobile_path());

                        $image->update([
                            'name'          => $new_name,
                            'is_rebuild'    => 1,
                        ]);

                        if(Setting::get('use_queue') == 'yes')
                            BuildImagesJob::dispatch($image->id)->onQueue('BuildImage');

                    }

                }
                
            }
            
        }elseif(is_numeric($this->option('album-id'))){
            
            $album = $this->albums->FindOrFail($this->option('album-id'));
            
            echo "Album ID: " . $album->id . "\n";
            echo "Album name: " . $album->name . "\n";
            
            foreach ($album->images as $image) {
                
                if($image->name != Transliterate::get($image->name)){
                    
                    echo $image->name . " => " . Transliterate::get($image->name) . "\n";
                    
                    $new_name = Transliterate::get($image->name);
                    
                    if(Storage::move($image->path(), $image->album->path() . "/" . $new_name)) {

                        if(Storage::has($image->thumb_path()))
                            Storage::delete($image->thumb_path());

                        if(Storage::has($image->mobile_path()))
                            Storage::delete($image->mobile_path());

                        $image->update([
                            'name'          => $new_name,
                            'is_rebuild'    => 1,
                        ]);

                        if(Setting::get('use_queue') == 'yes')
                            BuildImagesJob::dispatch($image->id)->onQueue('BuildImage');

                    }

                }
                
            }
        }
    }
}

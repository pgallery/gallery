<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\Images;
use App\Models\Albums;
use App\Jobs\BuildImagesJob;

use Setting;

class images_rename_to_transliterate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'images:rebuild {--album-id=} {--all}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Пересоздание миниатюр для фотографий';
    
    protected $images;
    protected $albums;
    
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Albums $albums, Images $images)
    {
        parent::__construct();
        
        $this->images = $images;
        $this->albums = $albums;
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

                if(Setting::get('use_queue') == 'yes')
                    BuildImagesJob::dispatch($image->id)->onQueue('BuildImage');            

                $this->images->findOrFail($image->id)->update(['is_rebuild'=> 1]);

            }
        }elseif(is_numeric($this->option('album-id'))){
            foreach ($this->albums->FindOrFail($this->option('album-id'))->images as $image) {

                if(Setting::get('use_queue') == 'yes')
                    BuildImagesJob::dispatch($image->id)->onQueue('BuildImage');            

                $this->images->findOrFail($image->id)->update(['is_rebuild'=> 1]);

            }
        }
    }
}

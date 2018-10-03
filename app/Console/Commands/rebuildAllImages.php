<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\Images;
use App\Jobs\BuildImagesJob;
use Setting;

class rebuildAllImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rebuildAllImages';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Пересоздание миниатюр для всех фотографий';
    
    protected $images;
    
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Images $images)
    {
        parent::__construct();
        
        $this->images = $images;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        
        foreach ($this->images->all() as $image) {

            if(Setting::get('use_queue') == 'yes')
                BuildImagesJob::dispatch($image->id)->onQueue('BuildImage');            
            
            $this->images->findOrFail($image->id)->update(['is_rebuild'=> 1]);
            
        }

    }
}

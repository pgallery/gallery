<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\Albums;
use App\Models\Images;

use Cache;

class albums_clear extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'albums:clear {--id=} {--force}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Очистить альбом от всех фотографий';

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
        if(empty($this->option('id')) and !is_numeric($this->option('id'))) {
            exit;
        }
        
        $album = $this->albums->FindOrFail($this->option('id'));
        echo "Альбом: '" . $album->name . "'\n";
        echo "Количество фото: " . $album->imagesCount() . "\n";

        foreach ($album->images as $image) {
            echo "Image ID: " . $image->id . "\n";
            $this->images->destroy($image->id);
            
            if($this->option('force'))
                $this->images->destroyImage($image->id);
            
        }
        
        $album->update(['images_id' => 0]);
        
        Cache::flush();
    }
}

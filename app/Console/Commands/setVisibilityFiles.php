<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\Albums;
use App\Models\Images;
use Storage;
use File;

class setVisibilityFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setVisibilityFiles {--force}';

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
        $albums = $this->albums->where('permission', 'All')->get();
          
        foreach ($albums as $album) {
            
            echo "\033[1m#\n";
            echo "# Album name: $album->name\n";
            echo "#\033[0m\n";
            
            $web_album_path = public_path("images/thumb/" . $album->url);
            
            if (!File::isDirectory($web_album_path))
                File::makeDirectory($web_album_path, 0755, true);
            
            foreach ($album->images as $image) {
                
                echo "File: " . $image->name . " ";
                
                $web_file_path = $web_album_path . "/" . $image->name;
                $FileExists = File::exists($web_file_path);
                $img = Storage::get($image->thumb_path());
                
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
        
        echo "\033[1mFinish:\033[0m\n\n";
        echo "Successful: $this->successful \n";
        echo "Skip: $this->skip \n";
        echo "Fail: $this->fail\n";

    }
}

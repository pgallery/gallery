<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\Albums;

use Transliterate;
use Cache;

class albums_change_url_to_transliterate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'albums:change_url_to_transliterate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Переименовывание URL всех альбомов в латиницу';

    protected $albums;    
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Albums $albums)
    {
        parent::__construct();
        
        $this->albums = $albums;        
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        
        foreach ($this->albums->all() as $album) {

            if($album->url != Transliterate::get($album->url)){
                echo "\033[0;32m Update album: " . $album->name . "\033[0m\n";
                echo $album->url . " => ". Transliterate::get($album->url) . "\n";
                $this->albums->findOrFail($album->id)->update(['url'=> Transliterate::get($album->url)]);
            }

        }
        
        Cache::flush();
    }
}

<?php

namespace App\Console;

//use DB;
//use Image;
//use File;

//use App\Models\Settings;
use App\Models\Images;
//use App\Models\Albums;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

//use Helper;
//use Setting;

use BuildImage;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {

        
        $schedule->call(function () {
           
                Images::select('id')->where('is_rebuild', 1)->chunk(25, function($images)
                {
                    foreach ($images as $image)
                    {
                        BuildImage::run($image['id']);
                    }
                });
           
        })->everyMinute();        
                
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}

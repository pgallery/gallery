<?php

namespace App\Console;

use App\Models\Images;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

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
           
                Images::select('id')->where('is_rebuild', 1)->chunk(50, function($images)
                {
                    foreach ($images as $image)
                    {
                        BuildImage::run($image['id']);
                    }
                    
                    return false;
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

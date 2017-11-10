<?php

namespace App\Console;

use App\Models\Images;
use App\Models\Archives;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

use Setting;
use BuildImage;
use Carbon\Carbon;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\queues::class,
        Commands\usermod::class,
        Commands\usercreate::class,
        Commands\setVisibilityFiles::class,
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
        
        $schedule->call(function () {
            
            Archives::select('id')->where('created_at', '<=', Carbon::now()->subHours(Setting::get('archive_save')))->chunk(50, function($archives)
                {
                    foreach ($archives as $archive)
                    {
                        Archives::destroyWithZipper($archive->id);
                    }
                    
                    return false;
                });
            
        })->hourly();
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

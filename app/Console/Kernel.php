<?php

namespace App\Console;

//use DB;
use Image;
use File;

use App\Models\Settings;
use App\Models\Images;
use App\Models\Albums;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

use Helper;
use Setting;

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
            
            // Create  Thumbs
            
                Images::where('is_thumbs', 0)->chunk(50, function($images)
                {
                    foreach ($images as $image)
                    {
                        $Albums = Albums::where('id', $image['albums_id'])->first();
                        
                        $file_path = Helper::getFullPathImage($image['id']);
                        $thumbs_path = Helper::getFullPathThumb($Albums['id']);
                        $thumbs_file = Helper::getFullPathThumbImage($image['id']);

                        if (File::exists($file_path))
                        {
                            if(!File::isDirectory($thumbs_path))
                                File::makeDirectory($thumbs_path, 0755, true);

                                // Делаем превью
//                                Image::make($file_path)
//                                    ->resize(Setting::get('thumbs_width'), null, function ($constraint) {
//                                        $constraint->aspectRatio();
//                                    })
//                                    ->save($thumbs_file);  
//                                Image::make($file_path)
//                                    ->resizeCanvas(Setting::get('thumbs_width'), Setting::get('thumbs_height'))
//                                    ->save($thumbs_file);
                            Image::make($file_path)
                                    ->fit(Setting::get('thumbs_width'), Setting::get('thumbs_height'))
                                    ->save($thumbs_file);
                                
                            if (File::exists($thumbs_file))
                                Images::where('id', $image['id'])->update(['is_thumbs' => 1]);
                        }                        
                        
                    }
                });            
           
        })->everyMinute();        
        
        
        $schedule->call(function () {
            
            // Create  Mobile Version
            
                Images::where('is_modile', 0)->chunk(50, function($images)
                {
                    foreach ($images as $image)
                    {
                        $Albums = Albums::where('id', $image['albums_id'])->first();
                        
                        $file_path = Helper::getFullPathImage($image['id']);
                        $modile_path = Helper::getFullPathMobile($Albums['id']);
                        $modile_file = Helper::getFullPathMobileImage($image['id']);                     

                        if (File::exists($file_path))
                        {
                            if(!File::isDirectory($modile_path))
                                File::makeDirectory($modile_path, 0755, true);                

                                // Делаем превью
                                Image::make($file_path)
                                    ->resize(Setting::get('mobile_width'), null, function ($constraint) {
                                        $constraint->aspectRatio();
                                    })
                                    ->save($modile_file);  
                                    
                            if (File::exists($modile_file))
                                Images::where('id', $image['id'])->update(['is_modile' => 1]);
                        }                        
                        
                    }
                });            
           
        })->everyMinute();          
        
        $schedule->call(function () {
            
            // get File Size
              
                Images::where('size', 0)->chunk(50, function($images)
                {
                    foreach ($images as $image)
                    {
                        $Albums = Albums::where('id', $image['albums_id'])->first();
                        $file_path = Helper::getFullPathImage($image['id']);

                        if (File::exists($file_path))
                        {
                            $SizeImage = Image::make($file_path)->filesize();
                            Images::where('id', $image['id'])->update(['size' => $SizeImage]);
                        }
                        
                    }
                });                

        })->everyMinute();
        
        $schedule->call(function () {
            
            // get thumbs File Size
              
                Images::where('thumbs_size', 0)->chunk(50, function($images)
                {
                    foreach ($images as $image)
                    {
                        $Albums = Albums::where('id', $image['albums_id'])->first();
                        $file_path = Helper::getFullPathThumbImage($image['id']);

                        if (File::exists($file_path))
                        {
                            $SizeImage = Image::make($file_path)->filesize();
                            Images::where('id', $image['id'])->update(['thumbs_size' => $SizeImage]);
                        }
                        
                    }
                });                

        })->everyMinute();
        
        $schedule->call(function () {
            
            // get Mobile File Size
              
                Images::where('modile_size', 0)->chunk(50, function($images)
                {
                    foreach ($images as $image)
                    {
                        $Albums = Albums::where('id', $image['albums_id'])->first();
                        $file_path = Helper::getFullPathMobileImage($image['id']);

                        if (File::exists($file_path))
                        {
                            $SizeImage = Image::make($file_path)->filesize();
                            Images::where('id', $image['id'])->update(['modile_size' => $SizeImage]);
                        }
                        
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

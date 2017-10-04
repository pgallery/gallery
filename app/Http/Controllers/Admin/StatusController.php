<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Images;

use Setting;

class StatusController extends Controller
{
    
    public function getStatus() {
        
        \Debugbar::disable();
        
        $error   = true;
        $rebuild = Images::where('is_rebuild', 1)->count();
        
        if($rebuild > 0){
            
            if(Setting::get('use_queue') != 'yes') {
                $run = exec("ps ax|grep -v grep|grep -v '/bin/sh'|grep -c 'artisan schedule:run'");

                if($run > 0) 
                    $error = false;
            }

            $error = false;
        }
        
        if(isset($run) and $run > 0)
            $output['run'] = $run;
        
        if($rebuild > 0)
            $output['rebuild'] = $rebuild;
        
        if(!$error)
            return \Response::json($output);
    }
}

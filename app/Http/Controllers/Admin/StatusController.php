<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Images;

class StatusController extends Controller
{
    public function getStatus() {
        
        \Debugbar::disable();
        
        $rebuild =  Images::where('is_rebuild', 1)->count();
        
        $output = [

            'rebuild' => $rebuild,            
        ];
        
        $result = '';
        
        if($rebuild > 0){
            $run = exec("ps ax|grep -v grep|grep -v '/bin/sh'|grep -c 'artisan schedule:run'");
        
            if($run > 0)
                $result .= 'Фоновых процессов: ' . $run . '. ';
        
            $result .= 'Изображений в очереди: ' . $rebuild . '. ';
        }
        
        if($result != '')
            $result = '<div class="alert alert-success" role="alert">' . $result .  '</div>';
            
            
        return $result;        
        
    }
}

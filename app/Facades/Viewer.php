<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class Viewer extends Facade{

    protected static function getFacadeAccessor() { 
        
        return 'viewer';
        
    }

}
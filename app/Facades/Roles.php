<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class Roles extends Facade{

    protected static function getFacadeAccessor() { 
        
        return 'roles';
        
    }

}
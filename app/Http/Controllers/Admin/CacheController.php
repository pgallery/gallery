<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Cache;

class CacheController extends Controller
{
    /*
     * Очистка всего кэша
     */
    public function flushCache() {
        
        Cache::flush();
        return back();
        
    }
}

<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Cache;

class CacheController extends Controller
{
    public function flushCache() {
        
        Cache::flush();
        return back()->withInput();
        
    }
}

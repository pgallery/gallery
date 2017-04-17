<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Config;

class Devmode
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!Auth::check())
        {
        
            Config::set('app.debug' , false);
            
        }
        
        return $next($request);
    }
}

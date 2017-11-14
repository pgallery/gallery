<?php

namespace App\Http\Middleware;

use Closure;
use Setting;

class HttpsProtocol
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
        if (!$request->secure() && Setting::get('use_ssl') === 'yes') {
            return redirect()->secure($request->getRequestUri());
        }

        return $next($request); 
        
    }
}

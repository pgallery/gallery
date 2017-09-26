<?php

namespace App\Http\Middleware;

use Auth;
use Closure;

class Google2fa
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
    	$user = Auth::user();

    	if (!$user)
            return redirect(route('login'));

    	if (!$user->google2fa_enabled)
            return $next($request);

    	if ($user->google2fa_ts)
            return $next($request);

    	return redirect(route('google2fa.auth'));
    }
}

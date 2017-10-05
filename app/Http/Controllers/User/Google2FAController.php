<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\User;

use Auth;
use Viewer;
use Google2FA;
use Carbon\Carbon;

class Google2FAController extends Controller
{
    protected $user;

    public function __construct(User $user) {
        $this->user  = $user;
    }
    
    /*
     * Двухфакторная авторизация
     */
    public function g2faAuth(Request $request) {
        
        if ($request->isMethod('get'))
            return Viewer::get('auth.google2fa.index');
        
        $user = Auth::user();
        
        $secret = $request->input('one_time_password');
        
        $timestamp = Google2FA::verifyKeyNewer($user->google2fa_secret, $secret, $user->google2fa_ts);
        
        if ($timestamp !== false) {
            $user->google2fa_ts = Carbon::now();
            $user->save();
        }else{
            return redirect()->route('google2fa.auth');
        }
        
        return redirect()->route('home');

    }
    
}

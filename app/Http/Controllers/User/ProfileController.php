<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Requests\ProfileRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

use App\Models\User;

use Auth;
use Viewer;
use Cache;
use Google2FA;

class ProfileController extends Controller
{
//    protected $user;
//
//    public function __construct(User $user) {
//        $this->user  = $user;
//    }
    
    /*
     * Вывод формы редактирования профиля
     */
    public function getProfile() {
        
//        https://www.sitepoint.com/2fa-in-laravel-with-google-authenticator-get-secure/

        $user = Auth::user();

        return Viewer::get('pages.profile', compact('user'));
        
    }
    
    /*
     * Сохранение изменений профиля
     */    
    public function putProfile(ProfileRequest $request) {
        
        $user = Auth::user();
        
        if($user->method == 'thisSite' and Hash::check($request->input('password'), Auth::user()->password)) {
        
            $user->update([
                'name'              => $request->input('name'),
                'email'             => $request->input('email'),
                'google2fa_enabled' => ($request->input('go2fa') == 'enable') ? '1' : '0',                
            ]);
            
            if($request->input('newPassword') && $request->input('newPassword') == $request->input('confirmPassword'))
                $user->update([
                    'password' => Hash::make($request->input('newPassword')),
                ]);
            
        } elseif($user->method != 'thisSite') {
            $user->update([
                'name' => $request->input('name'),
            ]);
        }
        
        Cache::forget('owner_' . Auth::user()->id . '_cache');
        
        return redirect()->route('edit-profile');
        
    }
    
    /*
     * Подключение двухфакторной авторизации для аккаунта
     */
    public function getEnable2FA(Request $request) {
        
        $user = Auth::user();
        
        if(!$user->google2fa_enabled) {
            $user->google2fa_enabled=true;
            $user->save();
        }
        
        if(!$user->google2fa_secret)
            $user->generateKey()->save();
        
        $secret = $user->google2fa_secret;
        $imageDataUri = Google2FA::getQRCodeInline(
            $request->getHttpHost(),
            $user->email,
            $user->google2fa_secret,
            200
        );
        
        return Viewer::get('google2fa.enable', compact('user', 'imageDataUri', 'secret'));
        
    }
    
    /*
     * Отключение двухфакторной авторизации для аккаунта
     */
    public function getDisable2FA(Request $request) {
        
        if ($request->isMethod('get'))
            return Viewer::get('google2fa.disabled');
        
        $user = Auth::user();
        
        $user->google2fa_enabled = false;
        $user->google2fa_ts = null;
        $user->google2fa_secret = null;
        $user->save();

        return redirect()->route('edit-profile');
        
    }
}

<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\User;
use Setting;

use Auth;
use Hash;

class UloginController extends Controller
{
    public function login(Request $request) {
        
        if(Setting::get('use_ulogin') != 'yes')
            return redirect()->route('403');
        
        $data = file_get_contents('http://ulogin.ru/token.php?token=' . $request->input('token') . '&host=' . $_SERVER['HTTP_HOST']);
        $user = json_decode($data, TRUE);
        
        $userData = User::where('email', $user['email'])->first();
        
        if (isset($userData->id))
        {
            Auth::loginUsingId($userData->id, TRUE);
            return back()->withInput();
        }
        else
        {
            $newUser = User::create([
                'name'      => $user['first_name'] . ' ' . $user['last_name'],
                'email'     => $user['email'],
                'password'  => Hash::make(str_random(8)),
                'method'    => $user['network'],
            ]);
            
            $RoleGuest = Roles::select('id')->where('name', 'guest')->first();
            $newUser->roles()->attach($RoleGuest->id);
            
            Auth::loginUsingId($newUser->id, TRUE);

            \Session::flash('flash_message', trans('interface.ActivatedSuccess'));

            return back()->withInput();
        }
    }
}

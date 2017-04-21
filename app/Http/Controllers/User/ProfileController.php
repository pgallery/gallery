<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

use App\User;

use Auth;
use Viewer;

class ProfileController extends Controller
{
    public function getProfile() {
        
        $user = User::find(Auth::user()->id);
        
        return Viewer::get('pages.profile', compact('user'));
    }
    
    public function putProfile(Request $request) {
        
        $user = User::find(Auth::user()->id);
               
        if($user->method == 'thisSite' and Hash::check($request->input('password'), Auth::user()->password))
        {
        
            $user->update([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
            ]);
            
            if($request->input('newPassword') && $request->input('newPassword') == $request->input('confirmPassword'))
                $user->update([
                    'password' => Hash::make($request->input('newPassword')),
                ]);
            
        }
        elseif($user->method != 'thisSite')
        {
            $user->update([
                'name' => $request->input('name'),
            ]);
        }
        
        return redirect()->route('edit-profile');
        
    }
}

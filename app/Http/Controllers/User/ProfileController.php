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
        
        if(Hash::check($request->input('password'), Auth::user()->password))
        {
         
            User::find(Auth::user()->id)->update([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
            ]);
            
            if($request->input('newPassword') && $request->input('newPassword') == $request->input('confirmPassword'))
                User::find(Auth::user()->id)->update([
                    'password' => Hash::make($request->input('newPassword')),
                ]);            
        }
        
        return redirect()->route('edit-profile');
        
    }
}

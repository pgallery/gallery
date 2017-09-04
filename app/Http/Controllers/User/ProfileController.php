<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

use App\Models\User;

use Auth;
use Viewer;
use Cache;

class ProfileController extends Controller
{
    protected $user;

    public function __construct(User $user) {
        $this->user  = $user;
    }
    
    public function getProfile() {
        
        $user = $this->user->find(Auth::user()->id);
        
        return Viewer::get('pages.profile', compact('user'));
    }
    
    public function putProfile(Request $request) {
        
        $user = $this->user->find(Auth::user()->id);
               
        if($user->method == 'thisSite' and Hash::check($request->input('password'), Auth::user()->password)) {
        
            $user->update([
                'name'  => $request->input('name'),
                'email' => $request->input('email'),
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
}

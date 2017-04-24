<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\User;
use Viewer;

class UsersController extends Controller
{
    
    public function getPage() {
        
        $users = User::all();
        
        
        return Viewer::get('admin.show_users', compact(
                'users'
        ));
    }
    
}

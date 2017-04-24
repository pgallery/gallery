<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Routing\Router;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\User;
use App\Models\Roles;
use Viewer;

class UsersController extends Controller
{
    
    public function getPage() {
        
        $users = User::all();
        
        return Viewer::get('admin.show_users', compact(
                'users'
        ));
    }
    
    public function getEdit(Router $router) {
        
        $user = User::find($router->input('id'));
        $roles = Roles::all();
        
//        print_r($roles);
//        exit;
        
        return Viewer::get('admin.user_edit', compact(
                'user',
                'roles'
        ));        
    }
}

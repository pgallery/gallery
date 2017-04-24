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
        $roles = Roles::pluck('display_name','id');
        $userRole = $user->roles->pluck('id','id')->toArray();

        return Viewer::get('admin.user_edit', compact(
                'user',
                'roles',
                'userRole'
        ));        
    }
    
    public function putUser(Router $router, Request $request) {
        
        $user = User::find($router->input('id'));
        $user->update($request->all());
        \DB::table('roles_user')->where('user_id', $router->input('id'))->delete();        
        
        foreach ($request->input('roles') as $key => $value) {
            $user->roles()->attach($value);
        }
        
        return redirect()->route('users');
        
    }
}

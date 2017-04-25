<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Routing\Router;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\User;
use App\Models\Roles;

use Viewer;
use Cache;

class UsersController extends Controller
{
    /*
     * Вывод списка пользователей
     */
    public function getPage() {
        
        $users = User::all();
        
        return Viewer::get('admin.show_users', compact(
                'users'
        ));
    }
    
    /*
     * Вывод формы редактирования выбранного пользователя
     */
    public function getEdit(Router $router) {
        
        $user       = User::find($router->input('id'));
        $roles      = Roles::pluck('display_name','id');
        $userRole   = $user->roles->pluck('id','id')->toArray();

        return Viewer::get('admin.user_edit', compact(
                'user',
                'roles',
                'userRole'
        ));        
    }
    
    /*
     * Сохранение изменений выбранного пользователя
     */    
    public function putUser(Router $router, Request $request) {
        
        $user = User::find($router->input('id'));
        $user->update($request->all());
        $user->roles()->sync($request->input('roles'));
        
        if (Cache::has(sha1('HelperIsAdmin_' . $router->input('id') . '_cache')))
            Cache::forget(sha1('HelperIsAdmin_' . $router->input('id') . '_cache'));        
        
        if (Cache::has(sha1('HelperIsAdminMenu_' . $router->input('id') . '_cache')))
            Cache::forget(sha1('HelperIsAdminMenu_' . $router->input('id') . '_cache'));

        if (Cache::has(sha1('Middleware.UsersRoles_' . $router->input('id') . '_cache')))
            Cache::forget(sha1('Middleware.UsersRoles_' . $router->input('id') . '_cache'));

        return redirect()->route('users');
        
    }
    
    /*
     * Удаление выбранного пользователя
     */
    public function deleteUser(Router $router) {
        
        User::destroy($router->input('id'));
        
        return redirect()->route('users');
        
    }
}

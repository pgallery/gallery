<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Routing\Router;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use App\Http\Controllers\Controller;

use App\User;
use App\Models\Roles;
use App\Models\Groups;
use App\Models\Albums;
use App\Models\Images;

use Viewer;
use Cache;

class UsersController extends Controller
{
    protected $user;
    protected $roles;
    protected $groups;
    protected $albums;
    protected $images;

    public function __construct(User $user, Roles $roles, Groups $groups, Albums $albums, Images $images) {
        $this->user    = $user;
        $this->roles   = $roles;
        $this->groups  = $groups;
        $this->albums  = $albums;
        $this->images  = $images;
    }

    /*
     * Вывод списка пользователей
     */
    public function getPage() {
        
        $users    = $this->user->all();
        $allRoles = $this->roles->pluck('display_name','id');

        return Viewer::get('admin.show_users', compact(
                'users',
                'allRoles'
        ));
    }
    
    /*
     * Вывод формы редактирования выбранного пользователя
     */
    public function getEdit(Router $router) {
        
        $user       = $this->user->find($router->input('id'));
        $roles      = $this->roles->pluck('display_name','id');
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
        
        $user = $this->user->find($router->input('id'));
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
        
        $id = $router->input('id');
        
        $this->groups->where('users_id', $id)->update(['users_id' => 1]);
        $this->albums->where('users_id', $id)->update(['users_id' => 1]);
        $this->images->where('users_id', $id)->update(['users_id' => 1]);
        
        $this->user->destroy($id);
        
        Cache::flush();
        
        return redirect()->route('users');
        
    }
    
    /*
     * Добавление нового пользователя
     */
    public function postCreateUser(UserRequest $request) {
        
        $this->user->createWithRoles($request->all());
        
        return redirect()->route('users');
    }
}

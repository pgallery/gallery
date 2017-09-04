<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Routing\Router;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use App\Http\Controllers\Controller;

use App\Models\User;
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
        
        Cache::forget('HelperIsAdmin_' . $router->input('id') . '_cache');        
        Cache::forget('HelperIsAdminMenu_' . $router->input('id') . '_cache');
        Cache::forget('Middleware.UsersRoles_' . $router->input('id') . '_cache');

        return redirect()->route('users');
        
    }
    
    /*
     * Проверяка удаляемого пользователя на наличие связанных объектов
     */
    public function deleteUserCheck(Router $router) {
        
        $id     = $router->input('id');
        $user   = $this->user->find($id);
        
        $issetObject = false;
        
        if($user->groupsCount() != 0) $issetObject = true;
        if($user->albumsCount() != 0) $issetObject = true;
        if($user->imagesCount() != 0) $issetObject = true;
        
        if($issetObject) {
            
            $allUsers = $this->user->pluck('name','id');
            unset($allUsers[$id]);

            return Viewer::get('admin.user_object', compact(
                'user',
                'allUsers'
            ));
            
        }
            
        $this->deleteUser($id);
        
        return redirect()->route('users');
        
    }
    
    /*
     * Удаление выбранного пользователя вместе с привязанными объектами
     */
    public function deleteForceUser(Router $router) {
        
        $id     = $router->input('id');
        $user   = $this->user->find($id);
        
        if($user->groupsCount() != 0) {
            
            $userGroups = $user->groups()->get();
            
            foreach ($userGroups as $group) {
                echo "GrID: " . $group->id . "<br>";
                $this->groups->deleteWithAlbums($group->id);
            }
            
        }
        
        if($user->albumsCount() != 0) {
            
            $userAlbums = $user->albums()->get();
            
            foreach ($userAlbums as $album) {
                echo "AlID: " . $album->id . "<br>";
                $this->albums->deleteWithImages($album->id);
            }
            
        }        
    
        if($user->imagesCount() != 0) {
            
            $userImages = $user->images()->get();
            
            foreach ($userImages as $image) {
                echo "ImID: " . $image->id . "<br>";
                $this->images->deleteCheckAlbumPreview($image->id);
            }
            
        }        
        
        $this->deleteUser($id);
        
        return redirect()->route('users');
        
    }
    
    /*
     * Перенос объектов к другому пользователю и удаление выбранного пользователя
     */
    public function deleteMigrateUser(Router $router, Request $request) {
        
        $id       = $router->input('id');
        $newOwner = $request->input('newOwner');

        $this->groups->where('users_id', $id)->update(['users_id' => $newOwner]);
        $this->albums->where('users_id', $id)->update(['users_id' => $newOwner]);
        $this->images->where('users_id', $id)->update(['users_id' => $newOwner]);        
        
        $this->deleteUser($id);
        
        return redirect()->route('users');
    }
    
    /*
     * Удаление выбранного пользователя
     */
    public function deleteUser($id) {
        
        $this->user->destroy($id);        
        Cache::flush();

    }
    
    /*
     * Добавление нового пользователя
     */
    public function postCreateUser(UserRequest $request) {
        
        $this->user->createWithRoles($request->all());
        
        return redirect()->route('users');
        
    }
}

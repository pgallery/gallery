<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Routing\Router;
use Illuminate\Http\Request;
use App\Http\Requests\GroupsRequest;
use App\Http\Controllers\Controller;

use App\Models\Groups;
use App\Models\Albums;
use App\Models\Images;

use Auth;
use Viewer;
use Cache;

class GroupsController extends Controller
{

    protected $groups;
    protected $albums;
    protected $images;

    public function __construct(Groups $groups, Albums $albums, Images $images) {
        
        $this->middleware('g2fa');
        
        $this->groups  = $groups;
        $this->albums  = $albums;
        $this->images  = $images;
    }
    
    /*
     *  Создание новой группы
     */
    public function postCreateGroup(GroupsRequest $request) {
        
        $input = $request->all();
        $input['users_id'] = Auth::user()->id;

        $this->groups->create($input);
        
        Cache::flush();
        
        return back();
        
    }
    
    /*
     * Удаление группы
     */
    public function deleteGroup(Router $router) {
        
        $this->groups->deleteWithAlbums($router->input('id'));
        
        return redirect()->route('admin');
        
    }
    
    /*
     * Вывод формы редактирования группы
     */
    public function getEditGroup(Router $router) {
        
        $group = $this->groups->find($router->input('id'));
        
        return Viewer::get('admin.group.edit', [
            'type'            => 'edit',
            'group'           => $group,
        ]);
    }
    
    /*
     * Сохранение изменений группы
     */
    public function putSaveGroup(Router $router, GroupsRequest $request) {
        
        $this->groups->find($router->input('id'))->update($request->all());
        
        Cache::flush();
        
        return redirect()->route('admin');
    }
    
}

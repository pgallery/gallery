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
    
    /*
     *  Создание новой группы
     */
    public function postCreateGroup(GroupsRequest $request) {
        
        $input = $request->all();
        $input['users_id'] = Auth::user()->id;

        Groups::create($input);
        
        Cache::forget(sha1('admin.show.groups'));
        
        return back();
        
    }
    
    /*
     * Удаление группы
     */
    public function deleteGroup(Router $router) {
        
        $Albums = Albums::where('groups_id', $router->input('id'))->get();
        foreach ($Albums as $album){
            
            Albums::destroy($album->id);
            Images::where('albums_id', $album->id)->delete();

        }
        
        Groups::destroy($router->input('id'));
        Cache::forget(sha1('admin.show.groups'));
        
        return redirect()->route('admin');
        
    }
    
    /*
     * Вывод формы редактирования группы
     */
    public function getEditGroup(Router $router) {
        
        $group = Groups::find($router->input('id'));
        
        return Viewer::get('admin.group_edit', [
            'type'            => 'edit',
            'group'           => $group,
        ]);        
        
    }    
    
    /*
     * Сохранение изменений группы
     */
    public function putSaveEditGroup(Router $router, GroupsRequest $request) {
        
        Groups::find($router->input('id'))->update($request->all());
        
        Cache::forget(sha1('admin.show.groups'));
        
        return redirect()->route('admin');
        
    }
    
}

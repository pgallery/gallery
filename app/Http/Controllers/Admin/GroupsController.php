<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Routing\Router;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Groups;
use App\Models\Albums;
use App\Models\Images;

use Auth;
use Viewer;
use Cache;

class GroupsController extends Controller
{
    public function postCreateGroup(Request $request) {
        
        Groups::create($request->all());
        
        Cache::forget(sha1('admin.show.groups'));
        
        return back();
        
    }
    
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

    public function getEditGroup(Router $router) {
        
        $group = Groups::find($router->input('id'));
        
        return Viewer::get('admin.group_edit', [
            'type'              => 'edit',
            'group'           => $group,
        ]);        
        
    }    
    
    public function putSaveEditGroup(Router $router, Request $request) {
        
        Groups::where('id', $router->input('id'))->update([
            'name'          => $request->input('name'),
            'users_id'      => Auth::user()->id,
        ]);
        
        Cache::forget(sha1('admin.show.groups'));
        
        return redirect()->route('admin');
        
    }  
    
}

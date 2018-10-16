<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Routing\Router;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Menu;
use App\Models\Tags;

use Viewer;
use Cache;

class MenuController extends Controller
{
    protected $menu;    
    protected $tags;
    
    public function __construct(Menu $menu, Tags $tags) {
        
        $this->middleware('g2fa');

        $this->menu = $menu;
        $this->tags = $tags;
    }
    
    /*
     * Вывод страницы редактирования меню
     */
    public function getMenu() {
        
        return Viewer::get('admin.menu.index', [
            'menus'   => $this->menu->all(),
            'allTags' => $this->tags->orderBy('name')->pluck('name','id')
        ]);
        
    }
    
    /*
     * Создание нового меню
     */    
    public function postCreateMenu(Request $request) {
        
        $sort = $this->menu->select('sort')->max('sort');

        $menu = new Menu();
        $menu->name = $request->input('name');
        $menu->sort = $sort + 1;
        $menu->save();
        
        $menu->tags()->sync($request->input('tags'));
        
        Cache::flush();
        
        return redirect()->route('menu');
    }
    
    /*
     * Переименовывание изображения
     */
    public function putMenu(Request $request) {
        
        $menu = $this->menu->findOrFail($request->input('id'));
        
        $menu->update(['name' => $request->input('newName')]);
        
        Cache::flush();
        
        return back();
    }
    
    /*
     * Отображение формы редактирование меню с типом 'tags'
     */
    public function getEditMenu(Router $router) {
        
        $menu = $this->menu->findOrFail($router->input('id'));
        
        $tags = $this->tags->orderBy('name')->pluck('name','id');
        $menuTags = $menu->tags->pluck('id','id')->toArray();
        
        if($menu->type != 'tags')
           return back();
        
        return Viewer::get('admin.menu.edit', compact([
            'menu',
            'tags',
            'menuTags'
        ]));
    }
    
    /*
     * Редактирование меню с типом 'tags'
     */
    public function putEditMenu(Request $request) {
        print_r($request->all());
        
        $menu = $this->menu->find($request->input('id'));
        
        $input['name']  = $request->input('name');
        $input['sort']  = $request->input('sort');
        
        if($request->input('show') == 'yes')
            $input['show'] = 'Y';
        else
            $input['show'] = 'N';
        
        $menu->update($input);
        $menu->tags()->sync($request->input('tags'));
        
        Cache::flush();
        
        return redirect()->route('menu');
    }
    
    /*
     * Включение/отключение отображения меню
     */
    public function showMenu(Router $router) {
        
        $menu = $this->menu->findOrFail($router->input('id'));
        
        $menu->update([
            'show' => ($router->input('option') == 'enable') ? 'Y' : 'N'
        ]);
        
        Cache::flush();
        
        return back();
    }
    
    /*
     * Удаление выбранного меню с типом 'tags'
     */
    public function deleteMenu(Router $router) {
        
        $menu = $this->menu->findOrFail($router->input('id'));
        
        if($menu->type == 'tags')
            $menu->destroy($router->input('id'));
        
        Cache::flush();
        
        return back();
    }    
}

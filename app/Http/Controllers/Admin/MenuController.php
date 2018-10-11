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
     * Редактирование меню с типом 'tags'
     */
    public function getEditMenu(Router $router) {
        echo $router->input('id');
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

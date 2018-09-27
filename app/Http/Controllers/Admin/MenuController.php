<?php

namespace App\Http\Controllers\Admin;

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
            'allTags' => $this->tags->pluck('name','id')
        ]);
        
    }
    
    /*
     * Создание нового меню
     */    
    public function postCreateMenu(Request $request) {
        $menu = new Menu();
        $menu->name = $request->input('name');
        $menu->save();
        
        $menu->tags()->sync($request->input('tags'));
        
        Cache::flush();
        
        return redirect()->route('menu');
    }
}

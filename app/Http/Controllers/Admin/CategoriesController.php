<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Routing\Router;
use Illuminate\Http\Request;
use App\Http\Requests\CategoriesRequest;
use App\Http\Controllers\Controller;

use App\Models\Categories;
use App\Models\Albums;
use App\Models\Images;

use Auth;
use Viewer;
use Cache;

class CategoriesController extends Controller
{
    protected $categories;
    protected $albums;
    protected $images;

    public function __construct(Categories $categories, Albums $albums, Images $images) {
        
        $this->middleware('g2fa');
        
        $this->categories  = $categories;
        $this->albums      = $albums;
        $this->images      = $images;
    }
    
    /*
     *  Создание новой категории
     */
    public function postCreateСategory(CategoriesRequest $request) {
        
        $input = $request->all();
        $input['users_id'] = Auth::user()->id;

        $this->categories->create($input);
        
        Cache::flush();
        
        return back();
        
    }
    
    /*
     * Удаление категории
     */
    public function deleteСategory(Router $router) {
        
        $this->categories->deleteWithAlbums($router->input('id'));
        
        return redirect()->route('admin');
        
    }
    
    /*
     * Вывод формы редактирования категории
     */
    public function getEditСategory(Router $router) {

        return Viewer::get('admin.category.edit', [
            'type'     => 'edit',
            'category' => $this->categories->find($router->input('id')),
        ]);
    }
    
    /*
     * Сохранение изменений категории
     */
    public function putSaveСategory(Router $router, CategoriesRequest $request) {
        
        $this->categories->find($router->input('id'))->update($request->all());
        
        Cache::flush();
        
        return redirect()->route('admin');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Routing\Router;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\User;
use App\Models\Categories;
use App\Models\Albums;
use App\Models\Roles;

use Setting;
use Transliterate;
use Viewer;
use Image;
use Cache;

class InterfaceController extends Controller
{
    
    protected $users;
    protected $categories;
    protected $albums;
    
    public function __construct(User $users, Categories $categories, Albums $albums) {
        $this->middleware('g2fa');
    
        $this->users      = $users;
        $this->categories = $categories;
        $this->albums     = $albums;
    }
    
    /*
     * Отображение общего списка групп/альбомов на странице администратора
     */
    public function getPage(Router $router){
        
        $categories = Cache::remember('admin.show.categories', Setting::get('cache_ttl'), function() {
            return $this->categories->all();
        });
        
        if($categories->count() == 0)
            return redirect()->route('wizard', ['id' => 1]);

        $categoriesArray = Cache::remember('admin.show.categoriesArray', Setting::get('cache_ttl'), function() {
            return $this->categories->orderBy('name')->pluck('name','id');
        });
        
        if($router->input('option') == 'byCategory' and is_numeric($router->input('id'))) {
            $byCategoryId = $router->input('id');
            $albums = Cache::remember('admin.show.albums.byCategory.' . $byCategoryId, Setting::get('cache_ttl'), function() use ($byCategoryId) {
                return $this->albums->where('categories_id', $byCategoryId)->get();
            });
        } else {
            $albums = Cache::remember('admin.show.albums', Setting::get('cache_ttl'), function() {
                return $this->albums->all();
            });
        }
        
        $usersArray = Cache::remember('admin.show.usersArray', Setting::get('cache_ttl'), function() {
            return $this->users->pluck('name','id');
        });
        
        $thumbs_dir = Setting::get('thumbs_dir');

        $transliterateMap = '';
        foreach (Transliterate::getMap() as $key => $value)
            $transliterateMap .= "'" . $key . "': '" . $value . "', "; 
        
        return Viewer::get('admin.page.index', compact(
            'thumbs_dir',
            'albums',
            'transliterateMap',
            'categories',
            'categoriesArray', 
            'usersArray'
        ));
        
    }
}

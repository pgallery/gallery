<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Routing\Router;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Categories;
use App\Models\Albums;
use App\Models\Images;

use Viewer;
use Transliterate;

class WizardController extends Controller
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
    
    public function getWizard(Router $router) {
        
        $categories_count = $this->categories->count();
        $albums_count     = $this->albums->count();
        if($albums_count !=0 )
            $album_id     = $this->albums->first()->id;
        else
            $album_id     = 0;
        $categoriesArray  = $this->categories->orderBy('name')->pluck('name','id');      
        
        $transliterateMap = '';
        foreach (Transliterate::getMap() as $key => $value)
            $transliterateMap .= "'" . $key . "': '" . $value . "', ";         
        
        return Viewer::get('admin.wizard.step-' . $router->input('id'), compact(
            'transliterateMap',                
            'categories_count',
            'albums_count',
            'album_id',
            'categoriesArray'
        ));

    }
    
}

<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Tags;

class TagsController extends Controller
{
    /*
     * Вывод списка тегов в JSON формате
     */
    public function getJSON() {
        
        \Debugbar::disable();
        
        $return = [];
        
        foreach (Tags::all() as $tag) {
            $return[] = $tag->name;
        }
        
        return \Response::json($return);
        
    }
}

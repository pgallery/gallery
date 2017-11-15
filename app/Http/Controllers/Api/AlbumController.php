<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Albums;
//use App\Models\Images;

class AlbumController extends Controller
{
    protected $albums;

    public function __construct(Albums $albums) {
        $this->albums = $albums;     
    }    
    
    public function getPage(Request $request) {
                
        if(!is_numeric($request->input('id')) or !$this->albums->where("id", $request->input('id'))->exists()) {
            
            $output['result']  = 'Failed';
            $output['message'] = 'Selected album not found.';
            $output['error']   = true;

            return response()->json($output);            
            
        }
        
        $id = $request->input('id');
        
        if(!is_numeric($request->input('count')))
            $count = 5;
        else
            $count = $request->input('count');
        
        $album = $this->albums->find($id);
        
        $list = [];
        $i = 0;
        
        $data = [
            'name'  => $album->name,
            'url'   => $album->http(),
            'thumb' => $album->thumbs->http_thumb_path(),
        ];
        
        foreach ($album->images()->paginate($count) as $image) {
            $list[$i]['name']  = $image->name;
            $list[$i]['url']   = $image->http_path();
            $list[$i]['thumb'] = $image->http_thumb_path();
            
            $i++;
        }
        
        $output['result'] = 'Successful';
        $output['data']   = $data;
        $output['list']   = $list;
        $output['error']  = false;

        return response()->json($output);
        
    }
}

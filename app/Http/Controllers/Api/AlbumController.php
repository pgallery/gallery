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
        
        $data = [];
        $i = 0;
        
        foreach ($album->images()->paginate($count) as $image) {
            $data[$i]['name']  = $image->name;
            $data[$i]['url']   = $image->http_path();
            $data[$i]['thumb'] = $image->http_thumb_path();
            
            $i++;
        }
        
//        print_r($data);
//        exit;
        $output['result'] = 'Successful';
        $output['data']   = $data;
        $output['error']  = false;
        
        
        return response()->json($output);
        
    }
}

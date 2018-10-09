<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Images;

class ImageController extends Controller
{
    protected $images;

    public function __construct(Images $images) {
        $this->images = $images;
    }
    
    public function getPage(Request $request) {
        
        if(!is_numeric($request->input('id')) or !$this->images->where("id", $request->input('id'))->exists()) {
            
            $output['result']  = 'Failed';
            $output['message'] = 'Selected image not found.';
            $output['error']   = true;

            return response()->json($output);            
            
        }
        
        $image = $this->images->find($request->input('id'));
        
        $data = [
            'name'  => $image->name,
            'url'   => $image->http_path(),
            'thumb' => $image->http_thumb_path(),
        ];
        
        $output['result'] = 'Successful';
        $output['data']   = $data;
        $output['error']  = false;

        return response()->json($output);        
    }
}

<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Albums;

class GalleryController extends Controller
{
    protected $albums;

    public function __construct(Albums $albums) {
        $this->albums = $albums;     
    }    
    
    public function getPage() {
        
        if(!$this->albums->where('permission', 'All')->where('images_id', '!=', '0')->exists()) {
            
            $output['result']  = 'Failed';
            $output['message'] = 'No albums available.';
            $output['error']   = true;

            return response()->json($output);
        }
        
        $list = [];
        $i = 0;
        $albums = $this->albums->where('permission', 'All')
                ->where('images_id', '!=', '0')
                ->orderBy('year', 'DESC')
                ->orderBy('created_at', 'DESC')
                ->get();
        
        foreach ($albums as $album) {
            $list[$i]['name']  = $album->name;
            $list[$i]['url']   = $album->http();
            $list[$i]['thumb'] = $album->thumbs->http_thumb_path();
            
            $i++;
        }
        
        $output['result'] = 'Successful';
        $output['list']   = $list;
        $output['error']  = false;
        
        return response()->json($output);
        
    }
}

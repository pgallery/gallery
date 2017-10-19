<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ApiController extends Controller
{
    public function getPage() {
        
        return response()->json([
            'result'  => 'Api run!',
            'error'   => false
        ]);
        
    }
}

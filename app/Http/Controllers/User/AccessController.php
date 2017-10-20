<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AccessController extends Controller
{
    public function getNoAccess() {
        
        return Viewer::get('errors.403');
        
    }
}

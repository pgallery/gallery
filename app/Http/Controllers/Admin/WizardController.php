<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Routing\Router;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Viewer;

class WizardController extends Controller
{
    
    public function getWizard(Router $router) {
        
        return Viewer::get('admin.wizard.step-' . $router->input('id'));
    
        
    }
    
}

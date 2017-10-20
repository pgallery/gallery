<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\User;
use App\Models\Categories;
use App\Models\Albums;
use App\Models\Images;

use Viewer;

class StatisticsController extends Controller
{
    
    protected $users;
    protected $categories;
    protected $albums;
    protected $images;

    public function __construct(User $users, Categories $categories, Albums $albums, Images $images) {
        $this->middleware('g2fa');

        $this->users      = $users;
        $this->categories = $categories;
        $this->albums     = $albums;
        $this->images     = $images;
    }
    
    public function getStatistics() {
        
        return Viewer::get('admin.statistics.index', [
            'count_users'         => $this->users->count(),
            'count_categories'    => $this->categories->count(),
            'count_albums'        => $this->albums->count(),
            'count_images'        => $this->images->count(),
            'summary_images_size' => \App\Helpers\Format::Bytes($this->images->sum('size')),
        ]);
        
    }
}

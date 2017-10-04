<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\User;
use App\Models\Groups;
use App\Models\Albums;
use App\Models\Images;

use Viewer;

class StatisticsController extends Controller
{
    
    protected $users;
    protected $groups;
    protected $albums;
    protected $images;

    public function __construct(User $users, Groups $groups, Albums $albums, Images $images) {
        $this->middleware('g2fa');

        $this->users  = $users;
        $this->groups = $groups;
        $this->albums = $albums;
        $this->images = $images;
    }
    
    public function getStatistics() {
        
        return Viewer::get('admin.statistics', [
            'count_users' => $this->users->count(),
            'count_groups'=> $this->groups->count(),
            'count_albums'=> $this->albums->count(),
            'count_images'=> $this->images->count(),
            'summary_images_size' => \Helper::formatBytes($this->images->sum('size')),
        ]);
        
    }
}

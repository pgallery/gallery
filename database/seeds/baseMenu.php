<?php

use Illuminate\Database\Seeder;

use App\Models\Menu;

class baseMenu extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(!Menu::where('type', 'categories')->exists()){
            Menu::create([
                'name' => 'Categories',
                'type' => 'categories',
                'show' => 'Y',
                'sort' => '1'
            ]);
        }
        
        if(!Menu::where('type', 'year')->exists()){
            Menu::create([
                'name' => 'Year',
                'type' => 'year',
                'show' => 'Y',
                'sort' => '2'
            ]);
        }
    }
}

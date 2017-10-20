<?php

use Illuminate\Database\Seeder;

use App\Models\Roles;

class baseRoles extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        Roles::create([
            'name'          => 'admin', 
            'display_name'  => 'Administrator',
            'description'   => 'Администратор галереи',
        ]);
        Roles::create([
            'name'          => 'moderator', 
            'display_name'  => 'Moderator',
            'description'   => 'Модератор галереи',
        ]);
        Roles::create([
            'name'          => 'operator', 
            'display_name'  => 'Оperator',
            'description'   => 'Оператор галереи',
        ]);
        Roles::create([
            'name'          => 'viewer', 
            'display_name'  => 'Viewer',
            'description'   => 'Зритель галереи',
        ]);
        Roles::create([
            'name'          => 'guest', 
            'display_name'  => 'Guest',
            'description'   => 'Гость галереи',
        ]);
        
    }
}

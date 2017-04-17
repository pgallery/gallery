<?php

use Illuminate\Database\Seeder;

use App\User;
use App\Models\Roles;
//use Hash;

class users extends Seeder
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
            'description'   => 'The God of World',
        ]);
        Roles::create([
            'name'          => 'moderator', 
            'display_name'  => 'Moderator',
            'description'   => 'The Moderator of World',
        ]);
        Roles::create([
            'name'          => 'guest', 
            'display_name'  => 'Guest',
            'description'   => 'The Guest of World',
        ]);

        User::create([
            'name'      => 'Admin',
            'email'     => 'admin@example.com',
            'password'  => bcrypt('admin'),
        ]);
        User::create([
            'name'      => 'Moder',
            'email'     => 'moder@example.com',
            'password'  => bcrypt('moder'),
        ]);

        $user = User::find(1);
        $user->roles()->attach(1);

        $user = User::find(2);
        $user->roles()->attach(2);
    }
}

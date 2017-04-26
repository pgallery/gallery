<?php

use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\Hash;

use App\User;
use App\Models\Roles;

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
            'description'   => 'Администратор галереи',
            'topanel'       => 'Y',
        ]);
        Roles::create([
            'name'          => 'moderator', 
            'display_name'  => 'Moderator',
            'description'   => 'Модератор галереи',
            'topanel'       => 'Y',
        ]);
        Roles::create([
            'name'          => 'operator', 
            'display_name'  => 'Оperator',
            'description'   => 'Оператор галереи',
            'topanel'       => 'Y',
        ]);
        Roles::create([
            'name'          => 'viewer', 
            'display_name'  => 'Viewer',
            'description'   => 'Зритель галереи',
            'topanel'       => 'N',
        ]);
        Roles::create([
            'name'          => 'guest', 
            'display_name'  => 'Guest',
            'description'   => 'Гость галереи',
            'topanel'       => 'N',
        ]);

        User::create([
            'name'      => 'Admin',
            'email'     => 'admin@example.com',
            'password'  => Hash::make('admin'),
            'method'    => 'thisSite',
        ]);
        User::create([
            'name'      => 'Moder',
            'email'     => 'moder@example.com',
            'password'  => Hash::make('moder'),
            'method'    => 'thisSite',
        ]);
        
        $RoleAdmin = Roles::select('id')->where('name', 'admin')->first();
        $RoleModer = Roles::select('id')->where('name', 'moderator')->first();
        
        $user = User::find(1);
        $user->roles()->attach($RoleAdmin->id);

        $user = User::find(2);
        $user->roles()->attach($RoleModer->id);        
        
    }
}

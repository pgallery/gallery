<?php

use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\Hash;

use App\Models\User;
use App\Models\Roles;

class baseUsers extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
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

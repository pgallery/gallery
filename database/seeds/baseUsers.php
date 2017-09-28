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
        
        $user           = new User();
        $user->name     = 'Admin';
        $user->email    = 'admin@example.com';
        $user->password = Hash::make('admin');
        $user->method   = 'thisSite';
        $user->save();
        
        $user->roles()->attach(Roles::select('id')->where('name', 'admin')->first());
        
    }
}

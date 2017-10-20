<?php

namespace App\Helpers;

use App\Models\User;

use Auth;

class Roles
{

    public static function is($roles){
        
        if(!Auth::check())
            return false;
        
        $user_roles = User::find(Auth::user()->id)->roles;
        
        if(is_array($roles))
        {
            foreach($roles as $role_name){
                $hasRole = self::is($role_name);
                if($hasRole)
                    return true;
            }
        }
        else
        {    
            foreach($user_roles as $user_role){
                if($user_role['name'] == $roles)
                    return true;
            }
        }        

        return false;

    }    
    
}
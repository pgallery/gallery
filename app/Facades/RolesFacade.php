<?php

namespace App\Facades;

use App\Models\User;

use Auth;
use Cache;

class RolesFacade
{

    public function is($roles){
        
        if(!Auth::check())
            return false;
        
        $id = Auth::user()->id;
        
        $user_roles = Cache::remember('user.roles.byId.' . $id, Setting::get('cache_ttl'), function() use($id) {
            return User::find($id)->roles;
        });
                
        if(is_array($roles)) {
            foreach($roles as $role_name){
                $hasRole = $this->is($role_name);
                if($hasRole)
                    return true;
            }
        } else {    
            foreach($user_roles as $user_role){
                if($user_role['name'] == $roles)
                    return true;
            }
        }        

        return false;

    }    
    
}
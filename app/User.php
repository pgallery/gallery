<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'method'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    
    public function roles()
    {
        return $this->belongsToMany('App\Models\Roles', 'roles_user');
    }
    
//    public function isAdmin($user)
//    {
//        $userRoles = $this->find($user)->roles->pluck('id','name')->toArray();
//        
//        if(in_array("admin", $userRoles))
//            return true;
//        else
//            return false;
//        
//    }    
    
    public function hasRole($roles)
    {
        $user_id = \Illuminate\Support\Facades\Auth::user()->id;
        $user_roles = $this->find($user_id)->roles;
        
        if(is_array($roles))
        {
            foreach($roles as $role_name){
                $hasRole = $this->hasRole($role_name);
                if($hasRole)
                    return true;
            }
        }
        else
        {    
            foreach($user_roles as $user_role){
                if($user_role->name == $roles)
                    return true;
            }
        }
        
        return false;
    }
}

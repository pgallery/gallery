<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;



class User extends Authenticatable
{
    use Notifiable;
    
    use SoftDeletes;    
    
    protected $dates = ['deleted_at'];
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
    
    public function hasRole($roles)
    {
        $user_id = \Illuminate\Support\Facades\Auth::user()->id;
        
        $user_roles = \Cache::remember(sha1('Middleware.UsersRoles_' . $user_id . '_cache'), 100, function() use ($user_id){
            return $this->find($user_id)->roles;
        });
        
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
                if($user_role['name'] == $roles)
                    return true;
            }
        }
        
        return false;
    }
}

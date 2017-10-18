<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

use App\Models\Roles;

class User extends Authenticatable
{
    use Notifiable;
    
    use SoftDeletes;    
    
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'name', 'email', 'password', 'method', 'google2fa_enabled', 'google2fa_ts', 'google2fa_secret'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];
    
    public function generateKey() {
        $google2fa = app('pragmarx.google2fa');
        $this->google2fa_secret = $google2fa->generateSecretKey();

        return $this;
    }
    
    public function roles() {
        return $this->belongsToMany('App\Models\Roles', 'roles_user');
    }
    
    public function categoriesCount() {
        return $this->hasMany('App\Models\Categories', 'users_id', 'id')->count();
    }

    public function categories() {
        return $this->hasMany('App\Models\Categories', 'users_id', 'id');
    }
    
    public function albumsCount() {
        return $this->hasMany('App\Models\Albums', 'users_id', 'id')->count();
    }

    public function albums() {
        return $this->hasMany('App\Models\Albums', 'users_id', 'id');
    }
    
    public function imagesCount() {
        return $this->hasMany('App\Models\Images', 'users_id', 'id')->count();
    }
   
    public function images() {
        return $this->hasMany('App\Models\Images', 'users_id', 'id');
    }
    
    public function hasRole($roles) {
        $user_id = \Illuminate\Support\Facades\Auth::user()->id;
        
        $user_roles = \Cache::remember('Middleware.UsersRoles_' . $user_id . '_cache', 100, function() use ($user_id){
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
    
    public function createWithRoles($input) {
        
        if(empty($input['method'])) $input['method']   = 'thisSite';
        
        $input['password'] = \Hash::make($input['password']);
        
        $user = $this->create($input);
        
        if(isset($input['roles'])) {
            
            foreach ($input['roles'] as $role) {
                $user->roles()->attach($role);
            }
            
        } else {
            
            $RoleGuest = Roles::select('id')->where('name', 'guest')->first();
            $user->roles()->attach($RoleGuest->id);
            
        }

        return $user->id;
    }
    
    public function destroyUser($id){
        
        $user = $this->withTrashed()->findOrFail($id);
        $user->forceDelete();
        
    }
}

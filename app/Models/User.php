<?php

namespace App\Models;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;


    public function getRouteKeyName()
    {
        return 'name';
    }

    public function roles()
    {
        return $this->belongsToMany('App\Models\Role', 'role_users', 'user_id', 'role_id');
    }

    public function hasAnyRole($roles)
    {
        if (Is_array($roles)) {
            foreach ($roles as $role) {
                if ($this->hasRole($role)) {
                    return true;
                }
            }
        } else {
            if ($this->hasRole($roles)) {
                return true;
            }
        }

        return false;
    }
 // $userRole = auth()->user()->role;
        // return $userRole && $userRole->slug === $role;
        public function hasRole($roles)
        {
            if (is_array($roles)) {
                return $this->roles()->whereIn('slug', $roles)->exists();
            }

            return $this->roles()->where('slug', $roles)->exists();
        }

    protected $fillable = [
        'name', 'email','place', 'password', 'pin_code','branch_id',
    ];


    protected $hidden = [
        'pin_code','password', 'remember_token',
    ];


    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}

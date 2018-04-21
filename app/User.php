<?php

namespace App;

use App\Http\Manager\Traits\AdminBuilder;
use App\Http\Manager\Auth\Database\HasPermissions;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable, AdminBuilder, HasPermissions;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'role', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    /*
    protected $hidden = [
        'password', 'remember_token',
    ];
    */
    protected $hidden = [
        'remember_token',
    ];

    
}

<?php

namespace Responsive;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array ( Marcello - retirado o gender apos pwd
     */
    protected $fillable = [
        'name','full_name','post_slug', 'email', 'password','admin','phone','photo','provider', 'provider_id','country','address','cpf_cnpj','created_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
}


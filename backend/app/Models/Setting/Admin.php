<?php

namespace App\Models\Setting;

use Laravel\Passport\HasApiTokens;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class Admin extends Model implements
    AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract
{
  use Authenticatable, Authorizable, CanResetPassword, HasApiTokens;
  protected $table = 'admins';
  protected $guarded = ['id'];

  function role(){
    return $this->hasOne('App\Models\Setting\Role','id','role_id');
  }

  function permissions() {
    return $this->hasMany('App\Models\Setting\RolePermission', 'role_id', 'role_id')->orderBy('permission_id','asc');
  }

  function store(){
    return $this->hasOne('App\Models\Setting\Store','id','store_id');
  }
}
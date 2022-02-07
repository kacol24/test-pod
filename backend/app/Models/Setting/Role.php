<?php

namespace App\Models\Setting;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
  protected $table = 'roles';
  protected $guarded = ['id'];

  function permissions() {
    return $this->belongsToMany('App\Models\Setting\FeaturePermission', 'role_permissions', 'role_id', 'permission_id');
  }
}
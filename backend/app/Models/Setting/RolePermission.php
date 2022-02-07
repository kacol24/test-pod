<?php

namespace App\Models\Setting;

use Illuminate\Database\Eloquent\Model;

class RolePermission extends Model
{
  protected $table = 'role_permissions';
  protected $guarded = ['id'];
  public $timestamps = false;

  function feature() {
    return $this->hasOne('App\Models\Setting\FeaturePermission', 'id', 'permission_id');
  }
}
<?php

namespace App\Models\Setting;

use Illuminate\Database\Eloquent\Model;

class FeaturePermission extends Model
{
  protected $table = 'feature_permissions';
  protected $guarded = ['id'];
  protected $fillbale = ['action_name','feature_id','order_weight'];
  public $timestamps = false;


  function title() {
    return $this->hasMany('App\Models\Setting\FeatureContent','module_id','id')->where('module','feature_permission')->where('language',session('language'))->first(array('value'))->value; 
  }
}
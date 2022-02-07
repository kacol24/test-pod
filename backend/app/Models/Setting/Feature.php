<?php

namespace App\Models\Setting;

use Illuminate\Database\Eloquent\Model;

class Feature extends Model
{
  protected $table = 'features';
  protected $guarded = ['id'];
  public $timestamps = false;

  public function scopeWithContent($query, $lang) {
    $query->join('feature_contents','feature_contents.module_id','=','features.id')->where('module','feature')->where('language', $lang);
  }

  function permissions(){
    return $this->hasMany('App\Models\Setting\FeaturePermission','feature_id','id');
  }
}
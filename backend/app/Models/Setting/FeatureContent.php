<?php

namespace App\Models\Setting;

use Illuminate\Database\Eloquent\Model;

class FeatureContent extends Model
{
  protected $table = 'feature_contents';
  protected $guarded = ['id'];
  public $timestamps = false;
}
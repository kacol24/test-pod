<?php

namespace App\Models\Business;

use Cache;
use Illuminate\Database\Eloquent\Model;

class BusinessArea extends Model
{
  protected $table = 'business_areas';
  protected $fillable = ['business_id','area'];
  public $timestamps = false;
}
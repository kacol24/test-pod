<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CouponClick extends Model
{
  protected $table = 'coupon_clicks';
  protected $fillable = ['business_id','token','date'];
  public $timestamps = false;
}

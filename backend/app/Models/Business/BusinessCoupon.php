<?php

namespace App\Models\Business;

use Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BusinessCoupon extends Model
{
  use SoftDeletes;
  protected $table = 'business_coupons';
  protected $guarded = ['id'];
}
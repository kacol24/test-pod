<?php

namespace App\Models\Order;

use Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderPlatform extends Model
{
    protected $table = 'order_platforms';
    protected $fillable = ['order_id','platform','platform_order_id'];
    public $timestamps = false;
}

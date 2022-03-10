<?php

namespace App\Models\Order;

use Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderShipping extends Model
{
    protected $table = 'order_shippings';
    protected $guarded = ['id'];
    public $timestamps = false;
}

<?php

namespace App\Models\Order;

use Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderStatus extends Model
{
    protected $table = 'order_status';
    protected $guarded = ['id'];
    public $timestamps = false;
}

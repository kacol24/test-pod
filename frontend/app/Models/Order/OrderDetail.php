<?php

namespace App\Models\Order;

use Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderDetail extends Model
{
    protected $table = 'order_details';
    protected $guarded = ['id'];
    public $timestamps = false;
}

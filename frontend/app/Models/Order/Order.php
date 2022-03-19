<?php

namespace App\Models\Order;

use Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    protected $table = 'orders';
    protected $guarded = ['id'];
    // public $timestamps = false;

    function store()
    {
        return $this->hasOne('App\Models\Store', 'id', 'store_id');
    }

    function platform($platform)
    {
        return $this->hasOne('App\Models\Order\OrderPlatform', 'order_id', 'id')->where('platform',$platform)->first();
    }

    function shipping()
    {
        return $this->hasOne('App\Models\Order\OrderShipping', 'order_id', 'id');
    }

    function details(){
        return $this->hasMany('App\Models\Order\OrderDetail','order_id','id');
    }
}

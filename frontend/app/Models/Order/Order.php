<?php

namespace App\Models\Order;

use App\Scopes\CurrentStoreScope;
use Cache;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public $timestamps = false;

    protected $table = 'orders';

    protected $guarded = ['id'];

    protected static function booted()
    {
        static::addGlobalScope(new CurrentStoreScope);
    }

    function store()
    {
        return $this->hasOne('App\Models\Store', 'id', 'store_id');
    }

    function platform($platform)
    {
        return $this->hasOne('App\Models\Order\OrderPlatform', 'order_id', 'id')->where('platform', $platform)->first();
    }

    function shipping()
    {
        return $this->hasOne('App\Models\Order\OrderShipping', 'order_id', 'id');
    }

    function details()
    {
        return $this->hasMany('App\Models\Order\OrderDetail', 'order_id', 'id');
    }

    public function status()
    {
        return $this->belongsTo(OrderStatus::class, 'status_id');
    }
}

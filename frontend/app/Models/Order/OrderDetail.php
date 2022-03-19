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

    function product() {
        return $this->hasOne('App\Models\Product\Product','id','product_id')->whereNull('deleted_at');
    }

    function sku() {
        return $this->hasOne('App\Models\Product\ProductSku','id','sku_id')->whereNull('deleted_at');
    }
}

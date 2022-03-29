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

    public function getFullAddressAttribute()
    {
        $address = $this->shipping;
        $template = '%address%<br>%subdistrict% %city%,<br>%state% %postcode%<br>%country%';

        return str_replace(
            [
                '%address%',
                '%subdistrict%',
                '%city%',
                '%state%',
                '%postcode%',
                '%country%'
            ],
            [
                $address->address,
                $address->subdistrict,
                $address->city,
                $address->state,
                $address->postcode,
                $address->country,
            ],
            $template);
    }

    public function getFormattedSubtotalAttribute()
    {
        return number_format($this->total_amount, 0, ',', '.');
    }

    public function getFormattedPromotionAttribute()
    {
        return number_format($this->discount_voucher, 0, ',', '.');
    }

    public function getFormattedShippingFeeAttribute()
    {
        return number_format($this->shipping_fee, 0, ',', '.');
    }

    public function getFormattedGrandTotalAttribute()
    {
        return number_format($this->final_amount, 0, ',', '.');
    }
}

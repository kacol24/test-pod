<?php

namespace App\Models\Product;

use Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductSku extends Model
{
    use SoftDeletes;
    public $timestamps = false;
    protected $table = 'product_skus';
    protected $guarded = ['id'];

    #Relation
    function product()
    {
        return $this->hasOne('App\Models\Product\Product', 'id', 'product_id')->withTrashed();
    }

    function option_detail1()
    {
        return $this->hasOne('App\Models\Product\ProductOptionDetail', 'key', 'option_detail_key1');
    }

    function option_detail2()
    {
        return $this->hasOne('App\Models\Product\ProductOptionDetail', 'key', 'option_detail_key2');
    }
}

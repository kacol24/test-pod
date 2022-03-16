<?php

namespace App\Models\Product;

use Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    protected $table = 'products';
    protected $guarded = ['id'];
    // public $timestamps = false;

    function images() {
        return $this->hasMany('App\Models\Product\ProductImage','product_id','id')->orderBy('order_weight','asc');
    }

    function skus() {
        return $this->hasMany('App\Models\Product\ProductSku','product_id','id')->whereNull('product_skus.deleted_at');
    }

    function mastersku($key1, $key2) {
        return $this->hasOne('App\Models\MasterProduct\MasterProductSku','product_id','master_product_id')->where('option_detail_key1', $key1)->where('option_detail_key2', $key2)->whereNull('master_product_skus.deleted_at')->first();
    }

    function firstsku() {
        return $this->hasOne('App\Models\Product\ProductSku','product_id','id')->orderBy('price','asc')->whereNull('product_skus.deleted_at')->first();
    }

    function masterproduct() {
        return $this->hasOne('App\Models\MasterProduct\MasterProduct','id','master_product_id');
    }

    function options() {
        return $this->hasMany('App\Models\Product\ProductOption', 'product_id', 'id');
    }

    function platform($platform) {
        return $this->hasOne('App\Models\Product\ProductPlatform','product_id','id')->where('platform', $platform)->whereNull('product_platforms.deleted_at')->first();
    }

    public function editor()
    {
        return $this->hasOne(ProductEditor::class, 'product_id');
    }
}

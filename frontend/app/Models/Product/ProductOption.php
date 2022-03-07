<?php

namespace App\Models\Product;

use Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductOption extends Model {
    protected $table = 'product_options';
    protected $guarded = ['id'];
    public $timestamps = false;

    function details() {
        return $this->hasMany('App\Models\Product\ProductOptionDetail', 'option_id', 'id');
    }
}

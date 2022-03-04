<?php

namespace App\Models\Product;

use Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductOptionDetail extends Model {
    protected $table = 'product_option_details';
    protected $guarded = ['id'];
    public $timestamps = false;
}

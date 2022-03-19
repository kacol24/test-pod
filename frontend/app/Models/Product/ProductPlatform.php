<?php

namespace App\Models\Product;

use Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductPlatform extends Model
{
    // use SoftDeletes;
    public $timestamps = false;
    protected $table = 'product_platforms';
    protected $fillable = ['product_id','platform','platform_product_id'];
}

<?php

namespace App\Models\Product;

use Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductEditor extends Model
{
    protected $table = 'product_editors';
    protected $guarded = ['id'];
    // public $timestamps = false;
}

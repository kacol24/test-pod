<?php

namespace App\Models\Product;

use Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductDesign extends Model
{
    protected $table = 'product_designs';
    protected $guarded = ['id'];
    // public $timestamps = false;
}

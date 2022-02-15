<?php

namespace Core\Models\Product;

use Cache;
use Illuminate\Database\Eloquent\Model;

class ProductOption extends Model
{
    // use SoftDeletes;
    public $timestamps = false;

    protected $table = 'master_product_options';

    protected $guarded = ['id'];

    function details()
    {
        return $this->hasMany('App\Models\Product\ProductOptionDetail', 'option_id', 'id');
    }
}

<?php

namespace App\Models\Product;

use Cache;
use Illuminate\Database\Eloquent\Model;

class ProductOptionDetail extends Model
{
    public $timestamps = false;

    protected $table = 'master_product_option_details';

    protected $guarded = ['id'];

    function option()
    {
        return $this->hasOne('App\Models\Product\ProductOption', 'id', 'option_id');
    }

    #Accessor
    public function getImageUrlAttribute()
    {
        return image_url('', $this->image);
    }
}

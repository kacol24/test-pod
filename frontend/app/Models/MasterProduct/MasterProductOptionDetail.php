<?php

namespace App\Models\MasterProduct;

use Cache;
use Illuminate\Database\Eloquent\Model;

class MasterProductOptionDetail extends Model
{
    public $timestamps = false;

    protected $table = 'master_product_option_details';

    protected $guarded = ['id'];

    function option()
    {
        return $this->hasOne('App\Models\MasterProduct\MasterProductOption', 'id', 'option_id');
    }

    public function firstSku()
    {
        return $this->hasOne(MasterProductSku::class, 'option_detail_key1', 'key');
    }
}

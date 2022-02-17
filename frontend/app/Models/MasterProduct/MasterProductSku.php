<?php

namespace App\Models\MasterProduct;

use Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterProductSku extends Model
{
    use SoftDeletes;

    public $timestamps = false;

    protected $table = 'master_product_skus';

    protected $guarded = ['id'];

    #Relation
    function product()
    {
        return $this->hasOne('App\Models\MasterProduct\MasterProduct', 'id', 'product_id')->withTrashed();
    }

    function option_detail1()
    {
        return $this->hasOne('App\Models\MasterProduct\MasterProductOptionDetail', 'key', 'option_detail_key1');
    }

    function option_detail2()
    {
        return $this->hasOne('App\Models\MasterProduct\MasterProductOptionDetail', 'key', 'option_detail_key2');
    }
}

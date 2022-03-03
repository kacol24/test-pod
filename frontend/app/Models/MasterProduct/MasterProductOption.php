<?php

namespace App\Models\MasterProduct;

use Cache;
use Illuminate\Database\Eloquent\Model;

class MasterProductOption extends Model
{
    // use SoftDeletes;
    public $timestamps = false;

    protected $table = 'master_product_options';

    protected $guarded = ['id'];

    function details()
    {
        return $this->hasMany('App\Models\MasterProduct\MasterProductOptionDetail', 'option_id', 'id');
    }
}

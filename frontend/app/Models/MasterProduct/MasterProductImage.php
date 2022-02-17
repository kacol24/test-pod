<?php

namespace App\Models\MasterProduct;

use Illuminate\Database\Eloquent\Model;

class MasterProductImage extends Model
{
    public $timestamps = false;

    protected $table = 'master_product_images';

    protected $guarded = ['id'];
}

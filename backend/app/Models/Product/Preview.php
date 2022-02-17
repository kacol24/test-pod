<?php

namespace App\Models\Product;

use Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Preview extends Model
{
    protected $table = 'master_product_previews';

    protected $guarded = ['id'];
}

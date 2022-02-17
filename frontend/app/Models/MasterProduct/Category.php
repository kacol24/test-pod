<?php

namespace App\Models\MasterProduct;

use Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;

    protected $table = 'master_categories';

    protected $guarded = ['id'];

    function scopeActive($query)
    {
        $query->where('is_active', 1);
    }

    function masterproducts($limit = 5)
    {
        return $this->belongsToMany('Core\Models\MasterProduct\MasterProduct', 'master_category_master_product')
                    ->limit($limit);
    }
}

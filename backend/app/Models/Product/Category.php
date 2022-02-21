<?php

namespace App\Models\Product;

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

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    function products($limit = 5)
    {
        return $this->belongsToMany('Core\Models\Product\Product', 'master_category_master_product')
                    ->limit($limit);
    }
}

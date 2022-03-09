<?php

namespace App\Models\MasterProduct;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Color extends Model
{
    use SoftDeletes;

    protected $table = 'master_product_colors';

    protected $guarded = ['id'];

    public $timestamps = false;

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function mockupColors()
    {
        return $this->hasMany(MockupColor::class, 'color_id');
    }
}

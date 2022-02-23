<?php

namespace App\Models\Product;

use Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Template extends Model
{
    protected $table = 'master_product_templates';

    protected $guarded = ['id'];

    public $timestamps = false;

    protected $with = [
        'previews',
        'designs'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function previews()
    {
        return $this->hasMany(Preview::class, 'template_id');
    }

    public function designs()
    {
        return $this->hasMany(Design::class, 'template_id');
    }
}

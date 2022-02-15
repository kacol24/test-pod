<?php

namespace App\Models\Product;

use Cache;
use Illuminate\Database\Eloquent\Model;

class ProductOption extends Model
{
    // use SoftDeletes;
    public $timestamps = false;

    protected $table = 'master_product_options';

    protected $guarded = ['id'];

    #Values

    function title()
    {
        $key = 'product_option_title_'.$this->id.'lang_'.session('language').session('store')->id.app_key();

        return Cache::tags(['store'.session('store')->id.app_key(), 'products'.session('store')->id.app_key()])
                    ->rememberForever($key, function () use ($key) {
                        return $this->hasMany('Core\Models\Product\ProductContent', 'module_id', 'id')
                                    ->where('module', 'product_option')->where('language', session('language'))
                                    ->first(['value'])->value;
                    });
    }

    #Relations
    function contents()
    {
        return $this->hasMany('Core\Models\Product\ProductContent', 'module_id', 'id')
                    ->where('module', 'product_option')->orderBy('id', 'asc');
    }

    function details()
    {
        return $this->hasMany('Core\Models\Product\ProductOptionDetail', 'option_id', 'id');
    }
}

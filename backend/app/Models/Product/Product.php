<?php

namespace App\Models\Product;

use Auth;
use Cache;
use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $table = 'master_products';

    protected $guarded = ['id'];

    function option1()
    {
        $lang = language($this);
        $key = 'product_option1_'.$this->product_id.$this->sku_id.'lang_'.$lang.$this->store_id.app_key();

        return Cache::tags(['store'.$this->store_id.app_key(), 'products'.$this->store_id.app_key()])
                    ->rememberForever($key, function () use ($lang) {
                        return $this->hasOne('App\Models\Product\ProductSku', 'id', 'sku_id')
                                    ->join('product_option_details', 'product_option_details.key', '=',
                                        'product_skus.option_detail_key1')
                                    ->join('product_options', 'product_options.id', '=',
                                        'product_option_details.option_id')
                                    ->join('product_contents', 'product_contents.module_id', '=', 'product_options.id')
                                    ->where('module', 'product_option')->where('language', $lang)->first()->value;
                    });
    }

    function option_detail1()
    {
        $lang = language($this);
        $key = 'product_option_detail1_'.$this->product_id.$this->sku_id.'lang_'.$lang.$this->store_id.app_key();

        return Cache::tags(['store'.$this->store_id.app_key(), 'products'.$this->store_id.app_key()])
                    ->rememberForever($key, function () use ($lang) {
                        return $this->hasOne('App\Models\Product\ProductSku', 'id', 'sku_id')
                                    ->join('product_option_details', 'product_option_details.key', '=',
                                        'product_skus.option_detail_key1')
                                    ->join('product_contents', 'product_contents.module_id', '=',
                                        'product_option_details.id')->where('module', 'product_option_detail')
                                    ->where('language', $lang)->first()->value;
                    });
    }

    function option2()
    {
        $lang = language($this);
        $key = 'product_option2_'.$this->product_id.$this->sku_id.'lang_'.$lang.$this->store_id.app_key();

        return Cache::tags(['store'.$this->store_id.app_key(), 'products'.$this->store_id.app_key()])
                    ->rememberForever($key, function () use ($lang) {
                        return $this->hasOne('App\Models\Product\ProductSku', 'id', 'sku_id')
                                    ->join('product_option_details', 'product_option_details.key', '=',
                                        'product_skus.option_detail_key2')
                                    ->join('product_options', 'product_options.id', '=',
                                        'product_option_details.option_id')
                                    ->join('product_contents', 'product_contents.module_id', '=', 'product_options.id')
                                    ->where('module', 'product_option')->where('language', $lang)->first()->value;
                    });
    }

    function option_detail2()
    {
        $lang = language($this);
        $key = 'product_option_detail2_'.$this->product_id.$this->sku_id.'lang_'.$lang.$this->store_id.app_key();

        return Cache::tags(['store'.$this->store_id.app_key(), 'products'.$this->store_id.app_key()])
                    ->rememberForever($key, function () use ($lang) {
                        return $this->hasOne('App\Models\Product\ProductSku', 'id', 'sku_id')
                                    ->join('product_option_details', 'product_option_details.key', '=',
                                        'product_skus.option_detail_key2')
                                    ->join('product_contents', 'product_contents.module_id', '=',
                                        'product_option_details.id')->where('module', 'product_option_detail')
                                    ->where('language', $lang)->first()->value;
                    });
    }

    function images()
    {
        return $this->hasMany('App\Models\Product\ProductImage', 'product_id', 'id')->orderBy('order_weight', 'asc');
    }

    function default_sku()
    {
        return $this->hasOne('App\Models\Product\ProductSku', 'product_id', 'id')
                    ->whereRaw('(option_detail_key1 is null and option_detail_key2 is null)');
    }

    function skus()
    {
        return $this->hasMany('App\Models\Product\ProductSku', 'product_id', 'id')->whereNull('deleted_at');
    }

    function sku()
    {
        return $this->hasOne('App\Models\Product\ProductSku', 'id', 'sku_id')->whereNull('deleted_at');
    }

    function categories()
    {
        return $this->belongsToMany('App\Models\Product\Category', 'master_category_master_product');
    }

    function options()
    {
        return $this->hasMany('App\Models\Product\ProductOption', 'product_id', 'id');
    }

    function nooption()
    {
        return $this->hasOne('App\Models\Product\ProductSku', 'product_id', 'id')
                    ->whereNull('product_skus.deleted_at');
    }

    function withoptions()
    {
        return $this->hasMany('App\Models\Product\ProductSku', 'product_id', 'id')->whereNotNull('option_detail_key1')
                    ->whereNull('product_skus.deleted_at')->orderBy('price', 'asc');
    }

    public function templates()
    {
        return $this->hasMany(Template::class, 'product_id');
    }

    public function previews()
    {
        return $this->hasManyThrough(Preview::class, Template::class, 'product_id', 'template_id');
    }

    public function designs()
    {
        return $this->hasManyThrough(Design::class, Template::class, 'product_id', 'template_id');
    }

    public function colors()
    {
        return $this->hasMany(Color::class, 'product_id');
    }
}

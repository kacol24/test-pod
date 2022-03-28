<?php

namespace App\Models\MasterProduct;

use Auth;
use Cache;
use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class MasterProduct extends Model
{
    use SoftDeletes;

    protected $table = 'master_products';

    protected $guarded = ['id'];

    protected $casts = [
        'enable_resize'          => 'boolean',
    ];

    protected $appends = [
        'thumbnail_url',
        'firstcategory_name',
        'base_cost',
    ];

    function option1()
    {
        $lang = language($this);
        $key = 'product_option1_'.$this->product_id.$this->sku_id.'lang_'.$lang.$this->store_id.app_key();

        return Cache::tags(['store'.$this->store_id.app_key(), 'products'.$this->store_id.app_key()])
                    ->rememberForever($key, function () use ($lang) {
                        return $this->hasOne('App\Models\MasterProduct\MasterProductSku', 'id', 'sku_id')
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
                        return $this->hasOne('App\Models\MasterProduct\MasterProductSku', 'id', 'sku_id')
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
                        return $this->hasOne('App\Models\MasterProduct\MasterProductSku', 'id', 'sku_id')
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
                        return $this->hasOne('App\Models\MasterProduct\MasterProductSku', 'id', 'sku_id')
                                    ->join('product_option_details', 'product_option_details.key', '=',
                                        'product_skus.option_detail_key2')
                                    ->join('product_contents', 'product_contents.module_id', '=',
                                        'product_option_details.id')->where('module', 'product_option_detail')
                                    ->where('language', $lang)->first()->value;
                    });
    }

    function thumbnail() {
        return $this->hasMany('App\Models\MasterProduct\MasterProductImage','product_id','id')->orderBy('order_weight','asc')->first(array('image'))->image;
    }

    function images()
    {
        return $this->hasMany('App\Models\MasterProduct\MasterProductImage', 'product_id', 'id')->orderBy('order_weight', 'asc');
    }

    function default_sku()
    {
        return $this->hasOne('App\Models\MasterProduct\MasterProductSku', 'product_id', 'id')
                    ->whereRaw('(option_detail_key1 is null and option_detail_key2 is null)');
    }

    function skus()
    {
        return $this->hasMany('App\Models\MasterProduct\MasterProductSku', 'product_id', 'id')->whereNull('deleted_at');
    }

    function sku()
    {
        return $this->hasOne('App\Models\MasterProduct\MasterProductSku', 'id', 'sku_id')->whereNull('deleted_at');
    }

    function skuvariants($variants)
    {
        $datas = $this->hasMany('App\Models\MasterProduct\MasterProductSku', 'product_id', 'id')->whereIn('option_detail_key1', $variants)->whereNull('deleted_at')->get();
        if(!$datas->count()) {
            $datas = $this->hasMany('App\Models\MasterProduct\MasterProductSku', 'product_id', 'id')->whereIn('option_detail_key2', $variants)->whereNull('deleted_at')->get();
        }

        return $datas;
    }

    function categories()
    {
        return $this->belongsToMany('App\Models\MasterProduct\Category', 'master_category_master_product', 'product_id');
    }

    function firstcategory() {
        return $this->belongsToMany('App\Models\MasterProduct\Category', 'master_category_master_product', 'product_id', 'category_id')->orderBy('master_categories.id','asc')->first();
    }

    function options()
    {
        return $this->hasMany('App\Models\MasterProduct\MasterProductOption', 'product_id', 'id');
    }

    function nooption()
    {
        return $this->hasOne('App\Models\MasterProduct\MasterProductSku', 'product_id', 'id')
                    ->whereNull('product_skus.deleted_at');
    }

    function withoptions()
    {
        return $this->hasMany('App\Models\MasterProduct\MasterProductSku', 'product_id', 'id')->whereNotNull('option_detail_key1')
                    ->whereNull('product_skus.deleted_at')->orderBy('price', 'asc');
    }

    function templates()
    {
        return $this->hasMany('App\Models\MasterProduct\Template', 'product_id', 'id');
    }

    function capacity()
    {
        return $this->hasOne('App\Models\MasterProduct\Capacity', 'id', 'capacity_id');
    }

    public function getThumbnailUrlAttribute()
    {
        if (config('filesystems.default') == 's3') {
            return Storage::url('b2b2c/masterproduct/' . $this->thumbnail());
        }

        return env('BACKEND_URL') . '/b2b2c/masterproduct/' . $this->thumbnail();
    }

    public function getFirstcategoryNameAttribute()
    {
        return $this->firstcategory()->name;
    }

    function colors()
    {
        return $this->hasMany('App\Models\MasterProduct\Color', 'product_id', 'id');
    }

    function mockupcolors()
    {
        return $this->hasMany('App\Models\MasterProduct\MockupColor', 'product_id', 'id');
    }

    public function getBaseCostAttribute()
    {
        return $this->default_sku->production_cost + $this->default_sku->fulfillment_cost;
    }
}

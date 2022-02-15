<?php

namespace App\Models\Product;

use Cache;
use Illuminate\Database\Eloquent\Model;

class ProductOptionDetail extends Model
{
    public $timestamps = false;

    protected $table = 'master_product_option_details';

    protected $guarded = ['id'];

    #Values

    function title($lang)
    {
        $store_id = (session('store')) ? session('store')->id : 1;
        $tag = 'product_option_title_'.$this->option_id.'lang_'.$lang.$store_id.app_key();
        $key = 'product_option_detail_title_'.$this->id.'lang_'.$lang.$store_id.app_key();

        return Cache::tags(['store'.$store_id.app_key(), $tag])->rememberForever($key, function () use ($lang) {
            return $this->hasMany('Core\Models\Product\ProductContent', 'module_id', 'id')
                        ->where('module', 'product_option_detail')->where('language', $lang)->first(['value'])->value;
        });
    }

    #Relations
    function contents()
    {
        return $this->hasMany('Core\Models\Product\ProductContent', 'module_id', 'id')
                    ->where('module', 'product_option_detail')->orderBy('id', 'asc');
    }

    function option()
    {
        return $this->hasOne('Core\Models\Product\ProductOption', 'id', 'option_id');
    }

    #Accessor
    public function getImageUrlAttribute()
    {
        return image_url('', $this->image);
    }
}

<?php

namespace App\Models\Product;

use Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductSku extends Model
{
    use SoftDeletes;
    public $timestamps = false;
    protected $table = 'product_skus';
    protected $guarded = ['id'];

    #Relation
    function product()
    {
        return $this->hasOne('App\Models\Product\Product', 'id', 'product_id');
    }

    function option_detail1()
    {
        return $this->hasOne('App\Models\Product\ProductOptionDetail', 'key', 'option_detail_key1');
    }

    function option_detail2()
    {
        return $this->hasOne('App\Models\Product\ProductOptionDetail', 'key', 'option_detail_key2');
    }

    public function scopeOfProduct($query, $productId)
    {
        return $query->where('product_id', $productId);
    }

    function getCombinationVariant($selections) {
        $combination = array();
        if($this->option_detail_key1) {
            foreach($selections as $selection) {
                foreach($selection['options'] as $i => $option) {
                    if(generate_key($selection['name'].$option['value']) == $this->option_detail_key1) {
                        $combination[] = $i;
                    }
                }
            }
        }
        if($this->option_detail_key2) {
            foreach($selections as $selection) {
                foreach($selection['options'] as $i => $option) {
                    if(generate_key($selection['name'].$option['value']) == $this->option_detail_key2) {
                        $combination[] = $i;
                    }
                }
            }
        }
        return $combination;
    }

    public function stock($product) {
        if($product->master_product_id) {
            $stock = $product->mastersku($this->option_detail_key1, $this->option_detail_key2)->stock;
            $capacity_stock = $product->masterproduct->capacity->capacity;
            if($capacity_stock < $stock) {
                $stock = $capacity_stock;
            }
            $stock = round($stock*$product->masterproduct->threshold/100);
        }else {
            $stock = $this->stock;
        }

        return $stock;
    }
}

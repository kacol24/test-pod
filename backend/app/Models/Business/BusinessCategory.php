<?php

namespace App\Models\Business;

use Cache;
use Illuminate\Database\Eloquent\Model;

class BusinessCategory extends Model
{
  protected $table = 'business_categories';
  protected $fillable = ['business_id','category_id'];
  public $timestamps = false;

  #Values
  function slug() {
    $key = 'business_category_slug_'.$this->category_id.'lang_'.session('language').session('store')->id.app_key();
    return Cache::tags(['store'.session('store')->id.app_key(), 'businesss'.session('store')->id.app_key()])->rememberForever($key, function() {
      return $this->hasMany('Core\Models\Product\CategorySlug','category_id','category_id')->orderBy('id','asc')->where('language',session('language'))->first()->value;
    }); 
  }

  function title() {
    $key = 'business_category_title_'.$this->category_id.'lang_'.session('language').session('store')->id.app_key();
    return Cache::tags(['store'.session('store')->id.app_key(), 'businesss'.session('store')->id.app_key()])->rememberForever($key, function() {
      return $this->hasMany('Core\Models\Product\CategoryContent','category_id','category_id')->where('language',session('language'))->where('keyword','title')->first(array('value'))->value; 
    }); 
  }

  #Relations
  function slugs(){
    return $this->hasMany('Core\Models\Product\CategorySlug','category_id','category_id')->orderBy('id','asc');
  }
}
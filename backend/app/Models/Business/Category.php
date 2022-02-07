<?php

namespace App\Models\Business;

use Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
  use SoftDeletes;
  protected $table = 'categories';
  protected $guarded = ['id'];

  #Scopes
  function scopeActive($query) {
    $query->where('is_active', 1);
  }

  #Values
  function parent() {
    return $this->hasOne('App\Models\Business\Category','id','parent_id');
  }

  function meta() {
    return json_decode($this->seo);
  }

  #Relations
  function business($limit = 5) {
    return $this->belongsToMany('App\Models\Business\Business', 'business', 'category_id', 'business_id')->limit($limit);
  }

    public function businesses()
    {
        return $this->belongsToMany(Business::class, 'business_categories');
  }
}

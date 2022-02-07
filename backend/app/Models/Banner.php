<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Banner extends Model
{
  use SoftDeletes;
  protected $table = 'banners';
  protected $guarded = ['id'];
  protected $fillable = ['title','url','is_active','start_date','end_date','type','order_weight', 'desktop_image', 'mobile_image'];

  #Scope
  public function scopeActive($query) {
      return $query->where('is_active', 1)
                   ->where('start_date', '<=', date('Y-m-d H:i'))
                   ->where(function ($query) {
                       $query->whereNull('end_date')
                             ->orWhere('end_date', '>=', date('Y-m-d H:i'));
                   });
  }
}

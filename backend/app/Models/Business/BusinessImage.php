<?php

namespace App\Models\Business;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BusinessImage extends Model
{
  protected $table = 'business_images';
  protected $guarded = ['id'];
  public $timestamps = false;

}
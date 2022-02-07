<?php

namespace App\Models\Business;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BusinessLink extends Model
{
  protected $table = 'business_links';
  protected $guarded = ['id'];
  public $timestamps = false;
}
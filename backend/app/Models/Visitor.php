<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Visitor extends Model
{
  protected $table = 'visitors';
  protected $fillable = ['business_id','token','date'];
  public $timestamps = false;
}

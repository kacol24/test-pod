<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ButtonClick extends Model
{
  protected $table = 'button_clicks';
  protected $fillable = ['business_id','token','date'];
  public $timestamps = false;
}

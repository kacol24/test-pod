<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContactClick extends Model
{
  protected $table = 'contact_clicks';
  protected $fillable = ['business_id','token','date'];
  public $timestamps = false;
}

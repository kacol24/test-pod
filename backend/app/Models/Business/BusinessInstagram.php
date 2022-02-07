<?php

namespace App\Models\Business;

use Cache;
use Illuminate\Database\Eloquent\Model;

class BusinessInstagram extends Model
{
  protected $table = 'business_instagrams';
  protected $fillable = ['business_id','ig_user_id','username','access_token','expired_at'];
  public $timestamps = false;

    public $incrementing = false;
    protected $primaryKey = null;
}

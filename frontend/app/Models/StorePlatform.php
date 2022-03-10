<?php

namespace App\Models;

use Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StorePlatform extends Model
{
    protected $table = 'store_platforms';
    protected $guarded = ['id'];
    public $timestamps = false;
}

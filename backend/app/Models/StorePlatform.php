<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StorePlatform extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'store_id',
        'platform',
        'platform_store_id',
    ];
}

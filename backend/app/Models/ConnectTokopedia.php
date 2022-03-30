<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConnectTokopedia extends Model
{
    protected $fillable = [
        'store_id',
        'store_name',
        'platform_id',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    public function connectTokopedias()
    {
        return $this->hasMany(ConnectTokopedia::class);
    }
}

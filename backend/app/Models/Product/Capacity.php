<?php

namespace App\Models\Product;

use Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Capacity extends Model
{
    protected $table = 'capacities';

    protected $guarded = ['id'];
}

<?php

namespace App\Models\Product;

use Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Option extends Model
{
    use SoftDeletes;

    protected $table = 'master_options';

    protected $guarded = ['id'];

    function details()
    {
        return $this->hasMany('App\Models\Product\OptionDetail', 'option_id', 'id');
    }
}

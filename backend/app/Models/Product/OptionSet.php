<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OptionSet extends Model
{
    use SoftDeletes;

    protected $table = 'master_option_sets';

    protected $guarded = ['id'];
}

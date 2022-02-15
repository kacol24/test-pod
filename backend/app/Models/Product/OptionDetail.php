<?php

namespace App\Models\Product;

use Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OptionDetail extends Model
{
    use SoftDeletes;

    protected $table = 'master_option_details';

    protected $guarded = ['id'];

    #Accessor
    public function getImageUrlAttribute()
    {
        return image_url('', $this->image);
    }
}

<?php

namespace App\Models\MasterProduct;

use Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Preview extends Model
{
    protected $table = 'master_template_previews';

    protected $guarded = ['id'];

    public $timestamps = false;

    public function template()
    {
        return $this->belongsTo(Template::class, 'template_id');
    }
}

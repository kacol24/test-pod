<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Design extends Model
{
    use HasFactory;

    protected $table = 'master_template_designs';

    protected $guarded = ['id'];

    public $timestamps = false;

    public function template()
    {
        return $this->belongsTo(Template::class, 'template_id');
    }
}

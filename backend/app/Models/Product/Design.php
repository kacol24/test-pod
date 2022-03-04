<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Design extends Model
{
    use SoftDeletes;

    protected $table = 'master_template_designs';

    protected $guarded = ['id'];

    public $timestamps = false;

    public function template()
    {
        return $this->belongsTo(Template::class, 'template_id');
    }

    public function mockupColors()
    {
        return $this->hasMany(MockupColor::class, 'design_id');
    }
}

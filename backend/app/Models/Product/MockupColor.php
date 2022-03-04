<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MockupColor extends Model
{
    protected $table = 'master_mockup_colors';

    public $timestamps = false;

    protected $guarded = ['id'];

    public function color()
    {
        return $this->belongsTo(Color::class, 'color_id');
    }

    public function design()
    {
        return $this->belongsTo(Design::class, 'design_id');
    }
}

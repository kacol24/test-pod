<?php

namespace Core\Models\Setting;

use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    public $table = 'links';

    protected $fillable = [
        'store_id',
        'is_active',
        'title',
        'image',
        'url',
        'order_weight',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order_weight');
    }
}

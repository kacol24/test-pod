<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StoreReferral extends Model
{
    const EXPIRED_THRESHOLD = 180; // days

    const REF_SESSION_KEY = 'rid';

    const COMMISSION_RATE = 2 / 100;

    use SoftDeletes;

    protected $casts = [
        'expired_at' => 'datetime',
    ];

    protected $fillable = [
        'ref_id', // yang ngajak
        'store_id', // yang diajak
        'expired_at', // +180 days from created_at
        'total_commission',
    ];

    public function downline()
    {
        return $this->belongsTo(Store::class, 'store_id');
    }

    public function upline()
    {
        return $this->belongsTo(Store::class, 'ref_id');
    }

    public function getFormattedTotalCommissionAttribute()
    {
        return number_format($this->total_commission, 0, ',', '.');
    }

    public function isExpired()
    {
        return $this->expired_at->diffInDays(today()) <= 0;
    }
}

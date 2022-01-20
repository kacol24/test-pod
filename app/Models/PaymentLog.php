<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'ref_id',
        'type',
        'request',
        'response',
    ];

    public function orderable()
    {
        return $this->morphTo();
    }
}

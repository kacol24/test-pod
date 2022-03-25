<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CanvasLog extends Model
{
    protected $fillable = [
        'type',
        'request',
        'response',
    ];

    protected $attributes = [
        'app' => 'frontend',
    ];
}

<?php

namespace App\Models;

use App\Models\Business\Business;
use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    protected $guarded = ['id'];

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function business()
    {
        return $this->belongsTo(Business::class);
    }
}

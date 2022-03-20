<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Store extends Model
{
    const SESSION_KEY = 'current_store';

    const REF_LENGTH = 8;

    const MAX_DOWNLINE = 10;

    protected $fillable = [
        'storename',
        'balance',
        'ref_code'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'store_users', 'store_id')->withPivot('role_id');
    }

    public function superAdmin()
    {
        return $this->users()->wherePivot('role_id', User::ROLE_ID_SUPER_ADMIN);
    }

    public function admins()
    {
        return $this->users()->wherePivot('role_id', User::ROLE_ID_ADMIN);
    }

    public function designers()
    {
        return $this->users()->wherePivot('role_id', User::ROLE_ID_DESIGNER);
    }

    public function finances()
    {
        return $this->users()->wherePivot('role_id', User::ROLE_ID_FINANCE);
    }

    public function balanceLogs()
    {
        return $this->hasMany(BalanceLog::class);
    }

    public function topups()
    {
        return $this->hasMany(Topup::class);
    }

    function platform($platform)
    {
        return $this->hasOne('App\Models\StorePlatform', 'store_id', 'id')->where('platform',$platform)->first();
    }

    function referral()
    {
        return $this->hasOne('App\Models\StoreReferral', 'store_id', 'id');
    }

    public function downlines()
    {
        return $this->hasMany(StoreReferral::class, 'ref_id');
    }

    public static function generateRefCode()
    {
        return strtoupper(Str::random(self::REF_LENGTH));
    }

    public function getTotalCommissionAttribute()
    {
        return $this->downlines->sum('total_commission');
    }

    public function getFormattedTotalCommissionAttribute()
    {
        return number_format($this->total_commission, 0, ',', '.');
    }

    public function referralSlotsLeft()
    {
        return self::MAX_DOWNLINE - $this->downlines->count();
    }
}

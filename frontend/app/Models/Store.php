<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    const SESSION_KEY = 'current_store';

    use HasFactory;

    protected $fillable = [
        'storename',
        'balance',
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
}

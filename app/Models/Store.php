<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    const SESSION_KEY = 'storename';

    use HasFactory;

    protected $fillable = [
        'storename',
        'balance',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot('role_id')->withTimestamps();
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
}

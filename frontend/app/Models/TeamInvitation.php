<?php

namespace App\Models;

use App\Scopes\CurrentStoreScope;
use Illuminate\Database\Eloquent\Model;

class TeamInvitation extends Model
{
    protected $fillable = [
        'email',
        'role_id',
    ];

    protected static function booted()
    {
        static::addGlobalScope(new CurrentStoreScope);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function getRoleNameAttribute()
    {
        switch ($this->role_id) {
            case User::ROLE_ID_SUPER_ADMIN:
                return 'Super Admin';
            case User::ROLE_ID_ADMIN:
                return 'Admin';
            case User::ROLE_ID_DESIGNER:
                return 'Designer';
            case User::ROLE_ID_FINANCE:
                return 'Finance';
        }
    }
}

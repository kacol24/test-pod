<?php

namespace App\Models;

use App\Notifications\QueuedVerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Arr;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    const ROLE_ID_SUPER_ADMIN = 1;

    const ROLE_ID_ADMIN = 2;

    const ROLE_ID_DESIGNER = 3;

    const ROLE_ID_FINANCE = 4;

    const OPTIONS_DESCRIBE = [
        'Marketer',
        'Content Creator',
        'Illustrator / Designer',
        'Organization',
        'Other',
    ];

    const OPTIONS_WHAT_WOULD_YOU_DO = [
        'Use warehousing services to sell my own products',
        'Start my first online business',
        'Grow my existing online business',
        'Sell merchandise to my followers/subscribers',
        'Sell merchandise for fundraising',
        'Ordering custom products for myself, my team or my event',
        'I\'m just playing around',
    ];

    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'description',
        'what_to_do',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $appends = [
        'role_id'
    ];

    public function stores()
    {
        return $this->belongsToMany(Store::class, 'store_users', 'user_id')
                    ->withPivot('role_id');
    }

    public function sendEmailVerificationNotification()
    {
        $this->notify(new QueuedVerifyEmail);
    }

    public function getInitialsAttribute()
    {
        $explode = explode(' ', strtoupper($this->name));

        $initials = collect([substr($explode[0], 0, 1)]);

        if (count($explode) > 1) {
            $initials->push(substr(Arr::last($explode), 0, 1));
        }

        return $initials->implode('');
    }

    public function getCurrentStoreAttribute()
    {
        return $this->stores()->where('store_id', session(Store::SESSION_KEY)->id)->first();
    }

    public function getRoleIdAttribute()
    {
        return StoreUser::where('store_id', session(Store::SESSION_KEY)->id)
                        ->where('user_id', $this->id)
                        ->first()->role_id;
    }
}

<?php

namespace App\Models\Business;

use App\Models\ButtonClick;
use App\Models\ContactClick;
use App\Models\CouponClick;
use App\Models\Visitor;
use App\Models\Wishlist;
use Auth;
use Cache;
use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Business extends Model
{
    const STATUS_APPROVED = 'approved';

    const STATUS_REJECTED = 'rejected';

    const TYPE_COMPLETE = 'complete';

    const TYPE_BASIC = 'basic';

    use SoftDeletes;

    protected $table = 'business';

    protected $guarded = ['id'];

    protected $dates = [
        'approved_at'
    ];

    #Value
    function firstcategory()
    {
        return $this->belongsToMany('App\Models\Business\Category', 'business_categories', 'business_id', 'category_id')
                    ->orderBy('business_categories.category_id', 'asc')->first();
    }

    function secondcategory()
    {
        return $this->belongsToMany('App\Models\Business\Category', 'business_categories', 'business_id', 'category_id')
                    ->orderBy('business_categories.category_id', 'asc')->skip(1)->first();
    }

    function wishlist()
    {
        if (Auth::check()) {
            $wishlist = $this->hasOne('App\Models\Cart\Wishlist', 'business_id', 'id')
                             ->where('user_id', Auth::user()->id)->first();
            if ($wishlist) {
                return $wishlist->id;
            }
        }

        return 0;
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    public function scopeComplete($query)
    {

        return $query->where('type', self::TYPE_COMPLETE);
    }

    public function scopeSlug($query, $slug)
    {
        return $query->where('slug', $slug);
    }

    #Relations
    function images()
    {
        return $this->hasMany('App\Models\Business\BusinessImage', 'business_id', 'id');
    }

    function connectInstagram()
    {
        return $this->hasOne('App\Models\Business\BusinessInstagram', 'business_id', 'id');
    }

    function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }

    function links()
    {
        return $this->hasMany('App\Models\Business\BusinessLink', 'business_id', 'id');
    }

    function categories()
    {
        return $this->hasMany('App\Models\Business\BusinessCategory', 'business_id', 'id');
    }

    function areas()
    {
        return $this->hasMany('App\Models\Business\BusinessArea', 'business_id', 'id');
    }

    function coupons()
    {
        return $this->hasMany('App\Models\Business\BusinessCoupon', 'business_id', 'id');
    }

    public function visitors()
    {
        return $this->hasMany(Visitor::class);
    }

    public function buttonClicks()
    {
        return $this->hasMany(ButtonClick::class);
    }

    public function contactClicks()
    {
        return $this->hasMany(ContactClick::class);
    }

    public function couponClicks()
    {
        return $this->hasMany(CouponClick::class);
    }
}

<?php

namespace App\Models;

use App\Models\Order\Order;
use App\Scopes\CurrentStoreScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BalanceLog extends Model
{
    const TYPE_IN = 'topup';

    const TYPE_OUT = 'order';

    const TYPE_COMMISSION = 'commission';

    const TYPE_REFUND = 'refund';

    protected $fillable = [
        'ref_id',
        'store_id',
        'type',
        'last',
        'given',
        'current',
    ];

    protected static function booted()
    {
        static::addGlobalScope(new CurrentStoreScope);
    }

    public function isDeposit()
    {
        return $this->type == self::TYPE_IN;
    }

    public function isWithdrawal()
    {
        return $this->type == self::TYPE_OUT;
    }

    public function isCommission()
    {
        return $this->type == self::TYPE_COMMISSION;
    }

    public function getFormattedGivenAttribute()
    {
        return number_format($this->given, 0, ',', '.');
    }

    public function topup()
    {
        return $this->belongsTo(Topup::class, 'ref_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'ref_id')->with('store');
    }

    public function getRefAttribute()
    {
        if ($this->isDeposit()) {
            return $this->topup;
        }

        return $this->order;
    }
}

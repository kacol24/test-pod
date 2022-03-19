<?php

namespace App\Models;

use App\Scopes\CurrentStoreScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BalanceLog extends Model
{
    const TYPE_IN = 'topup';

    const TYPE_OUT = 'order';

    const TYPE_COMMISSION = 'commission';

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

    public function getFormattedGivenAttribute()
    {
        return number_format($this->given, 0, ',', '.');
    }

    public function topup()
    {
        return $this->belongsTo(Topup::class, 'ref_id');
    }

    public function ref()
    {
        return $this->morphTo(__FUNCTION__, 'ref_type', 'ref_id');
    }

    public function getRefAttribute()
    {
        //if ($this->isDeposit()) {
        //    return json_encode($this->topup);
        //}
        //
        //return json_encode($this->order);

        return $this->topup;
    }
}

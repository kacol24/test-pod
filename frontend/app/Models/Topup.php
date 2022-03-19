<?php

namespace App\Models;

use App\Scopes\CurrentStoreScope;
use App\Services\WalletService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Topup extends Model
{
    const STATUS_PENDING_PAYMENT = 1;

    const STATUS_PAID = 2;

    const STATUS_CANCELLED = 3;

    const STATUS_PAYMENT_FAILED = 4;

    use HasFactory;

    protected $fillable = [
        'store_id',
        'total',
        'payment',
        'status_id',
        'ref_id',
    ];

    protected static function booted()
    {
        static::addGlobalScope(new CurrentStoreScope);
    }

    public function scopePending($query)
    {
        return $query->where('status_id', self::STATUS_PENDING_PAYMENT);
    }

    public function getSerialNumberAttribute()
    {
        return 'TU'.$this->created_at->format('Ymd').$this->store_id.'-'.$this->id;
    }

    public function getFormattedTotalAttribute()
    {
        return number_format($this->total, 0, ',', '.');
    }

    public function paymentLogs()
    {
        return $this->morphMany(PaymentLog::class, 'orderable');
    }

    public function setAsPaid()
    {
        \DB::beginTransaction();

        $this->status_id = self::STATUS_PAID;
        $this->save();
        $wallet = new WalletService($this->store_id);
        $wallet->deposit($this->total, $this->id);

        \DB::commit();
    }

    public function balanceLog()
    {
        return $this->morphOne(BalanceLog::class, 'ref', 'ref_type', 'ref_id');
    }
}

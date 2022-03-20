<?php

namespace App\Services;

use App\Models\BalanceLog;
use App\Models\Store;

class WalletService
{
    protected $storeId;

    private $store;

    public function __construct($storeId)
    {
        $this->storeId = $storeId;
        $this->store = Store::find($this->storeId);
    }

    public function deposit($amount, $refId, $type = BalanceLog::TYPE_IN)
    {
        $remainingBalance = $this->getBalance() + $amount;

        $this->makeTransaction($refId, $type, $amount, $remainingBalance);

        session([Store::SESSION_KEY => $this->store]);

        return $this->store;
    }

    public function order($amount, $refId)
    {
        if ($this->getBalance() == 0) {
            throw new \Exception('Balance is empty');
        }

        if ($amount > $this->getBalance()) {
            throw new \Exception('Insufficient funds');
        }

        $remainingBalance = $this->getBalance() - $amount;

        $this->makeTransaction($refId, BalanceLog::TYPE_OUT, $amount, $remainingBalance);

        session([Store::SESSION_KEY => $this->store]);

        return $this->store;
    }

    public function commission($amount, $refId)
    {
        $remainingBalance = $this->getBalance() + $amount;

        $this->makeTransaction($refId, BalanceLog::TYPE_COMMISSION, $amount, $remainingBalance);

        return $this->store;
    }

    public function getBalance()
    {
        return $this->store->balance;
    }

    protected function makeTransaction($refId, $type, $amount, $remaining)
    {
        \DB::beginTransaction();
        $this->store->balanceLogs()->create([
            'ref_id'  => $refId,
            'type'    => $type,
            'last'    => $this->getBalance(),
            'given'   => $amount,
            'current' => $remaining,
        ]);
        $this->store->balance = $remaining;
        $this->store->save();
        \DB::commit();
    }
}

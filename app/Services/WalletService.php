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

    public function deposit($amount, $refId)
    {
        $remainingBalance = $this->getBalance() + $amount;

        \DB::beginTransaction();
        $this->store->balanceLogs()->create([
            'ref_id'  => $refId,
            'type'    => BalanceLog::TYPE_IN,
            'last'    => $this->getBalance(),
            'given'   => $amount,
            'current' => $remainingBalance,
        ]);
        $this->store->balance = $remainingBalance;
        $this->store->save();
        \DB::commit();

        session([Store::SESSION_KEY => $this->store]);

        return $this->store;
    }

    public function withdraw($amount, $refId)
    {
        if ($this->getBalance() == 0) {
            throw new \Exception('Balance is empty');
        }

        if ($amount > $this->getBalance()) {
            throw new \Exception('Insufficient funds');
        }

        $remainingBalance = $this->getBalance() - $amount;

        \DB::beginTransaction();
        $this->store->balanceLogs()->create([
            'ref_id'  => $refId,
            'type'    => BalanceLog::TYPE_OUT,
            'last'    => $this->getBalance(),
            'given'   => $amount,
            'current' => $remainingBalance,
        ]);
        $this->store->balance = $remainingBalance;
        $this->store->save();
        \DB::commit();

        session([Store::SESSION_KEY => $this->store]);

        return $this->store;
    }

    public function getBalance()
    {
        return $this->store->balance;
    }
}

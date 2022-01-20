<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\BalanceLog;
use App\Models\Topup;

class WalletController extends Controller
{
    public function index()
    {
        $transactions = BalanceLog::latest()->paginate(10);
        $paymentMethods = collect(config('payment.xendit.channels'))->groupBy('type');

        $pendingTransactions = Topup::pending()->get();

        return view('account.mywallet', compact('transactions', 'paymentMethods', 'pendingTransactions'));
    }
}

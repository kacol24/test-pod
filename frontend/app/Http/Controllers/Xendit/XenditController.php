<?php

namespace App\Http\Controllers\Xendit;

use App\Http\Controllers\Controller;
use App\Models\Topup;
use App\Services\PaymentService;
use Illuminate\Http\Request;

class XenditController extends Controller
{
    public function virtualAccount(Request $request)
    {
        $topup = Topup::find($request->order_id);
    }

    public function creditCard(Request $request)
    {
        $topup = Topup::find($request->order_id);

        $paymentService = new PaymentService(PaymentService::TYPE_CC, $topup, $request->all());
        $success = $paymentService->charge($topup->total);

        if (! $success) {
            return back();
        }

        return redirect()->route('topup.success', ['order_id' => $topup->id]);
    }

    public function ewallet(Request $request)
    {
        $topup = Topup::find($request->order_id);

        $paymentService = new PaymentService(PaymentService::TYPE_EWALLET, $topup, $request->all());
        $paymentService->charge($topup->total);
    }
}

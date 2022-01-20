<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\Topup;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class TopupController extends Controller
{
    public function index(Request $request)
    {
        abort_if(! $request->has('order_id'), 404, 'Order not found.');

        $orderId = explode('-', $request->order_id);
        $topupId = Arr::last($orderId);
        $topup = Topup::findOrFail($topupId);

        if ($topup->status_id == Topup::STATUS_PAID) {
            return redirect()->route('topup.success', ['order_id' => $topup->id]);
        }

        $paymentChannel = Arr::first(config('payment.xendit.channels'), function ($item) use ($topup) {
            return $item['display_name'] == $topup->payment;
        });

        $data = [
            'order'          => $topup,
            'paymentType'    => $paymentChannel['type'],
            'paymentChannel' => $paymentChannel,
            'log'            => json_decode(
                optional(
                    $topup->paymentLogs
                        ->sortByDesc('created_at')
                        ->firstWhere('type', 'payment_'.strtolower($paymentChannel['type']))
                )->response
            ),
        ];

        return view('account.topup', $data);
    }

    public function store(Request $request)
    {
        $storeId = session(Store::SESSION_KEY)->id;

        $topup = Topup::create([
            'store_id' => $storeId,
            'ref_id'   => Str::random(60),
            'total'    => $request->amount,
            'payment'  => $request->payment_method,
        ]);

        $paymentChannel = Arr::first(config('payment.xendit.channels'), function ($item) use ($topup) {
            return $item['display_name'] == $topup->payment;
        });

        if ($paymentChannel['type'] == PaymentService::TYPE_VA) {
            $paymentService = new PaymentService($paymentChannel['type'], $topup, $request->all());
            $paymentService->charge($topup->total);
        }

        return redirect()->route('topup', ['order_id' => $topup->serial_number]);
    }

    public function success(Request $request)
    {
        abort_if(! $request->has('order_id'), 404, 'Order not found.');

        $topup = Topup::findOrFail($request->order_id);

        $data = [
            'orderable' => $topup,
        ];

        return view('account.topup-success', $data);
    }
}

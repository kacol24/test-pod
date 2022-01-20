<?php

namespace App\Http\Controllers\Xendit;

use App\Http\Controllers\Controller;
use App\Models\Topup;
use Illuminate\Http\Request;
use Xendit\EWallets;
use Xendit\Exceptions\ApiException;
use Xendit\VirtualAccounts;

class XenditWebhookController extends Controller
{
    function notifyEwallet(Request $request)
    {
        $data = $request->input('data');
        $topup = Topup::where('ref_id', $data['reference_id'])->first();

        $paymentRef = $topup->ref_if;
        if ($paymentRef) {
            try {
                $eWalletChargeStatus = EWallets::getEWalletChargeStatus($data['id']);
                if ($eWalletChargeStatus['status'] == 'SUCCEEDED') {
                    $order = $topup;
                    if ($order) {
                        $order_id = $topup->serial_number;
                        if ($data['capture_amount'] == $order->total) {
                            $topup->setAsPaid();
                            $response = '0,Success,'.$order_id.','.date('Y-m-d H:i:s');
                        } else {
                            $response = "1,Invalid Amount,,,";
                        }
                    } else {
                        $response = '1,Invalid Order Id,,,';
                    }
                } else {
                    $response = '1,Invalid Payment,,,';
                }
            } catch (ApiException $e) {
                $response = '1,Invalid Callback,,,';
            }
            $topup->paymentLogs()->create([
                'type'     => 'notify_ewallet',
                'request'  => json_encode($request->all()),
                'response' => $response,
            ]);
        } else {
            $response = '1,Invalid Payment Ref,,,';
        }

        echo $response;
    }

    function notifyVACreated(Request $request)
    {
        $topup = Topup::where('ref_id', $request->input('external_id'))->first();
        $paymentRef = $topup->ref_id;
        if ($paymentRef) {
            $response = '0,Success,,,';
            $topup->paymentLogs()->create([
                'type'     => 'vacreated',
                'request'  => json_encode($request->all()),
                'response' => $response,
            ]);
        } else {
            $response = '1,Invalid Payment Ref,,,';
        }

        echo $response;
    }

    function notifyVAPaid(Request $request)
    {
        $topup = Topup::where('ref_if', $request->input('external_id'))->first();
        $paymentRef = $topup->ref_id;
        if ($paymentRef) {
            try {
                $va = VirtualAccounts::getFVAPayment($request->input('payment_id'));
                if (isset($va['payment_id'])) {
                    $order = $topup;
                    if ($order) {
                        $order_id = $topup->serial_number;
                        if ($request->input('amount') == $order->total) {
                            $topup->setAsPaid();
                            $response = '0,Success,'.$order_id.','.date('Y-m-d H:i:s');
                        } else {
                            $response = "1,Invalid Amount,,,";
                        }
                    } else {
                        $response = '1,Invalid Order Id,,,';
                    }
                } else {
                    $response = '1,Invalid Payment,,,';
                }
            } catch (ApiException $e) {
                $response = '1,Invalid Callback,,,';
            }
            $topup->paymentLogs()->create([
                'type'     => 'vapaid',
                'request'  => json_encode($request->all()),
                'response' => $response,
            ]);
        } else {
            $response = '1,Invalid Payment Ref,,,';
        }

        echo $response;
    }
}

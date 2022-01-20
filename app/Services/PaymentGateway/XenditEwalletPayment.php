<?php

namespace App\Services\PaymentGateway;

use Xendit\EWallets;

class XenditEwalletPayment extends XenditPaymentGateway
{
    const CHANNEL_CODE_OVO = 'ID_OVO';

    const CHANNEL_CODE_SHOPEEPAY = 'ID_SHOPEEPAY';

    protected $checkoutMethod = 'ONE_TIME_PAYMENT';

    public function charge($amount)
    {
        $ewalletChannel = collect(config('payment.xendit.channels'))->first(function ($channel) {
            return $channel['display_name'] == $this->orderable->payment;
        });

        $params = [
            'reference_id'    => $this->orderable->ref_id,
            'currency'        => $this->currency,
            'amount'          => (int) $amount,
            'checkout_method' => $this->checkoutMethod,
            'channel_code'    => $ewalletChannel['bank_code'],
        ];

        if ($this->orderable->payment == self::CHANNEL_CODE_OVO) {
            $properties = [
                'mobile_number' => $this->hp($this->request['phone']),
            ];
        } else {
            $properties = [
                'success_redirect_url' => route('topup.success', ['order_id' => $this->orderable->serial_number]),
            ];
        }

        $params['channel_properties'] = $properties;

        $log = $this->logRequest('payment_ewallet', json_encode($params));

        $response = EWallets::createEWalletCharge($params);
        $log->response = json_encode($response);
        $log->save();

        if ($response['is_redirect_required']) {
            if ($ewalletChannel['bank_code'] == self::CHANNEL_CODE_SHOPEEPAY) {
                return $response['actions']['mobile_deeplink_checkout_url'];
            } else {
                return $response['actions']['desktop_web_checkout_url'];
            }
        } else {
            return route('topup.success', ['order_id' => $this->orderable->serial_number]);
        }
    }

    private function hp($nohp)
    {
        // kadang ada penulisan no hp 0811 239 345
        $nohp = str_replace(" ", "", $nohp);
        // kadang ada penulisan no hp (0274) 778787
        $nohp = str_replace("(", "", $nohp);
        // kadang ada penulisan no hp (0274) 778787
        $nohp = str_replace(")", "", $nohp);
        // kadang ada penulisan no hp 0811.239.345
        $nohp = str_replace(".", "", $nohp);
        // kadang ada penulisan no hp 0811-239-345
        $nohp = str_replace("-", "", $nohp);

        // cek apakah no hp mengandung karakter + dan 0-9
        if (! preg_match('/[^+0-9]/', trim($nohp))) {
            // cek apakah no hp karakter 1-3 adalah +62
            if (substr(trim($nohp), 0, 3) == '+62') {
                $nohp = trim($nohp);
            } // cek apakah no hp karakter 1 adalah 0
            elseif (substr(trim($nohp), 0, 1) == '0') {
                $nohp = '+62'.substr(trim($nohp), 1);
            } // cek apakah no hp karakter 1-2 adalah 62
            elseif (substr(trim($nohp), 0, 2) == '62') {
                $nohp = '+'.trim($nohp);
            }
        }

        return $nohp;
    }
}

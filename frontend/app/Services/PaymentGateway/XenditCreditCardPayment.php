<?php

namespace App\Services\PaymentGateway;

use Xendit\Cards;
use Xendit\Exceptions\ApiException;

class XenditCreditCardPayment extends XenditPaymentGateway
{
    const STATUS_CAPTURED = 'CAPTURED';

    public function charge($amount)
    {
        $params = [
            'token_id'          => $this->request['token_id'],
            'authentication_id' => $this->request['authentication_id'],
            'external_id'       => $this->orderable->ref_id,
            'amount'            => $amount,
            'card_cvn'          => $this->request['cvv'],
            'capture'           => false,
        ];

        $chargeLog = $this->logRequest('charge_cc', json_encode($params));

        try {
            $charge = Cards::create($params);
            $chargeLog->response = json_encode($charge);
            $chargeLog->save();
        } catch (ApiException $e) {
            $chargeLog->response = 'There is already a credit card charge with that external id';
            $chargeLog->save();

            return false;
        }

        $captureLog = $this->logRequest('capture_cc', json_encode(array_merge(
                [
                    'id'     => $charge['id'],
                    'amount' => $this->orderable->total,
                ],
                [
                    'key' => config('services.xendit.secret_key'),
                ]
            )
        ));

        $capture = Cards::capture($charge['id'], ['amount' => $this->orderable->total]);
        $captureLog->response = json_encode($capture);
        $captureLog->save();

        if ($capture['status'] != self::STATUS_CAPTURED) {
            return false;
        }

        $this->orderable->setAsPaid();

        return true;
    }
}

<?php

namespace App\Services\PaymentGateway;

use Xendit\VirtualAccounts;

class XenditVaPayment extends XenditPaymentGateway
{
    protected $isClosed = true;

    protected $isSingleUse = true;

    public function charge($amount)
    {
        $params = [
            'external_id'     => $this->orderable->ref_id,
            'bank_code'       => $this->orderable->payment,
            'name'            => $this->getName(auth()->user()->name),
            'is_closed'       => $this->isClosed,
            'expected_amount' => (int) $amount,
            'is_single_use'   => $this->isSingleUse,
        ];

        $log = $this->logRequest('payment_va', json_encode($params));

        $response = VirtualAccounts::create($params);
        $log->response = json_encode($response);
        $log->save();
    }

    private function getName($name)
    {
        return preg_replace('/[0-9]+/', '', preg_replace('/[^A-Za-z0-9\-]/', '', $name));
    }
}

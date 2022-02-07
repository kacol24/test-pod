<?php

namespace App\Services\PaymentGateway;

use App\Services\PaymentGateway;
use Xendit\Xendit;

abstract class XenditPaymentGateway implements PaymentGateway
{
    protected $currency = 'IDR';

    protected $orderable;

    protected $request;

    public function __construct($orderable, array $request)
    {
        Xendit::setApiBase(config('services.xendit.api_url'));
        Xendit::setApiKey(config('services.xendit.secret_key'));
        $this->orderable = $orderable;
        $this->request = $request;
    }

    protected function logRequest($type, $request)
    {
        return $this->orderable->paymentLogs()->create([
            'type'     => $type,
            'request'  => $request,
            'response' => '',
        ]);
    }
}

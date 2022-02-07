<?php

namespace App\Services;

use App\Services\PaymentGateway\XenditCreditCardPayment;
use App\Services\PaymentGateway\XenditEwalletPayment;
use App\Services\PaymentGateway\XenditVaPayment;

class PaymentService implements PaymentGateway
{
    const TYPE_VA = 'VA';

    const TYPE_CC = 'CC';

    const TYPE_EWALLET = 'eWallet';

    protected $orderable;

    protected $request;

    private $paymentGateway;

    public function __construct($type, $orderable, array $request)
    {
        $this->request = $request;
        $this->orderable = $orderable;
        $this->paymentGateway = $this->makeGateway($type);
    }

    /**
     * @return \App\Services\PaymentGateway\XenditEwalletPayment|\App\Services\PaymentGateway\XenditVaPayment
     */
    public function getPaymentGateway()
    {
        return $this->paymentGateway;
    }

    public function charge($amount)
    {
        $this->paymentGateway->charge($amount);
    }

    private function makeGateway($type)
    {
        switch ($type) {
            case self::TYPE_EWALLET:
                return new XenditEwalletPayment($this->orderable, $this->request);
            case self::TYPE_VA:
                return new XenditVaPayment($this->orderable, $this->request);
            case self::TYPE_CC:
                return new XenditCreditCardPayment($this->orderable, $this->request);
            default:
                throw new \RuntimeException('Unsupported payment gateway type: '.$type);
        }
    }
}

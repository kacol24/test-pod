<?php

namespace App\Services;

interface PaymentGateway
{
    public function charge($amount);
}

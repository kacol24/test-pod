<?php
return [
    'xendit' => [
        'display_name' => 'Xendit', 'active' => 1,
        'channels'     => [
            'vamandiri'  => [
                'bank_code' => 'MANDIRI', 'display_name' => 'Mandiri', 'type' => 'VA', 'fee_pct' => 0,
                'fee_flat'  => 3500, 'logo' => 'frontend/payment/mandiri.png',
            ],
            'vabni'      => [
                'bank_code' => 'BNI', 'display_name' => 'BNI', 'type' => 'VA', 'fee_pct' => 0, 'fee_flat' => 3500,
                'logo'      => 'frontend/payment/bni.png',
            ],
            'vabri'      => [
                'bank_code' => 'BRI', 'display_name' => 'BRI', 'type' => 'VA', 'fee_pct' => 0, 'fee_flat' => 3500,
                'logo'      => 'frontend/payment/briepay.png',
            ],
            'vapermata'  => [
                'bank_code' => 'PERMATA', 'display_name' => 'Permata', 'type' => 'VA', 'fee_pct' => 0,
                'fee_flat'  => 3500, 'logo' => 'frontend/payment/permatabank.png',
            ],
            'vabca'      => [
                'bank_code' => 'BCA', 'display_name' => 'BCA', 'type' => 'VA', 'fee_pct' => 2, 'fee_flat' => 2000,
                'logo'      => 'frontend/payment/bca.png',
            ],
            'ovo'        => [
                'bank_code' => 'ID_OVO', 'display_name' => 'OVO', 'type' => 'eWallet', 'fee_pct' => 1.5,
                'fee_flat'  => 0, 'logo' => 'frontend/payment/ovo.png',
            ],
            'dana'       => [
                'bank_code' => 'ID_DANA', 'display_name' => 'DANA', 'type' => 'eWallet', 'fee_pct' => 1.5,
                'fee_flat'  => 0, 'logo' => 'frontend/payment/dana.png',
            ],
            'linkaja'    => [
                'bank_code' => 'ID_LINKAJA', 'display_name' => 'LinkAja', 'type' => 'eWallet', 'fee_pct' => 1.5,
                'fee_flat'  => 0, 'logo' => 'frontend/payment/linkaja.png',
            ],
            'shopeepay'  => [
                'bank_code' => 'ID_SHOPEEPAY', 'display_name' => 'Shopee Pay', 'type' => 'eWallet', 'fee_pct' => 1.5,
                'fee_flat'  => 0, 'logo' => 'frontend/payment/shopee.png',
            ],
            'creditcard' => [
                'bank_code' => '', 'display_name' => 'Credit Card', 'type' => 'CC', 'fee_pct' => 2.5,
                'fee_flat'  => 2000, 'logo' => 'frontend/payment/cc.png',
            ],
        ],
    ],
];

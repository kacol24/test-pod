<?php

namespace App\Enums;

final class Permissions
{
    const DESIGN = 'design';

    const ORDERS = 'orders';

    const STORES = 'stores';

    const REFERRALS = 'referrals';

    const WALLET = 'wallet';

    const TEAM = 'team';

    const ALL = [
        self::DESIGN,
        self::ORDERS,
        self::STORES,
        self::REFERRALS,
        self::WALLET,
        self::TEAM,
    ];
}

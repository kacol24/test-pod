<?php

use App\Enums\Permissions;
use App\Models\User;

return [
    User::ROLE_ID_SUPER_ADMIN => Permissions::ALL,
    User::ROLE_ID_ADMIN       => Permissions::ALL,
    User::ROLE_ID_DESIGNER    => [
        Permissions::DESIGN,
        //Permissions::ORDERS,
        Permissions::STORES,
        Permissions::REFERRALS,
        //Permissions::WALLET,
        //Permissions::TEAM,
    ],
    User::ROLE_ID_FINANCE     => [
        //Permissions::DESIGN,
        Permissions::ORDERS,
        Permissions::STORES,
        Permissions::REFERRALS,
        Permissions::WALLET,
        //Permissions::TEAM,
    ],
];

<?php

use App\Enums\Permissions;
use App\Models\User;
use Illuminate\Support\Arr;

return [
    User::ROLE_ID_SUPER_ADMIN => Permissions::ALL,
    User::ROLE_ID_ADMIN       => Permissions::ALL,
    User::ROLE_ID_DESIGNER    => Arr::except(Permissions::ALL, [Permissions::WALLET]),
    User::ROLE_ID_FINANCE     => Arr::except(Permissions::ALL, [Permissions::DESIGN]),
];

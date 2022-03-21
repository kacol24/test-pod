<?php

namespace App\Providers;

use App\Enums\Permissions;
use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::before(function ($user) {
            if (in_array($user->role_id, [User::ROLE_ID_SUPER_ADMIN, User::ROLE_ID_ADMIN])) {
                return true;
            }

            return false;
        });

        foreach (Permissions::ALL as $permission) {
            Gate::define($permission, function (User $user) use ($permission) {
                return in_array($permission, config('role_permission')[$user->role_id]);
            });
        }
    }
}

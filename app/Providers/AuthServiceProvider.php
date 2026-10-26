<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // Gates sesuai kebutuhan Slide 5 & 14
        Gate::define('admin-only', function ($user) {
            return $user->isAdmin();
        });

        Gate::define('manage-master-data', function ($user) {
            return $user->isAdmin();
        });

        Gate::define('manage-users', function ($user) {
            return $user->isAdmin();
        });

        Gate::define('manage-sales', function ($user) {
            return in_array($user->role, ['admin', 'staff']);
        });

        Gate::define('manage-purchases', function ($user) {
            return in_array($user->role, ['admin', 'staff']);
        });

        Gate::define('cancel-transaction', function ($user) {
            return $user->isAdmin();
        });

        Gate::define('customer-only', function ($user) {
            return $user->isCustomer();
        });

        Gate::define('staff-or-admin', function ($user) {
            return in_array($user->role, ['admin', 'staff']);
        });
    }
}

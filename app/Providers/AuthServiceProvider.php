<?php

namespace App\Providers;

use App\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Passport::routes();
        Passport::tokensExpireIn(now()->addMinutes(60));
        Passport::refreshTokensExpireIn(now()->addDays(30));

        Gate::define('action', function (User $user, $model) {
            return $user->hasAccess("view_users") ||
                $user->hasAccess("add_users") ||
                $user->hasAccess("edit_users") ||
                $user->hasAccess("delete_users") ||
                $user->hasAccess('view_supermarkets') ||
                $user->hasAccess('add_supermarkets') ||
                $user->hasAccess('edit_supermarkets') ||
                $user->hasAccess('delete_supermarkets') ||
                $user->hasAccess('view_supermarket_branches') ||
                $user->hasAccess('add_supermarket_branches') ||
                $user->hasAccess('edit_supermarket_branches') ||
                $user->hasAccess('delete_supermarket_branches') ||
                $user->hasAccess('view_categories') ||
                $user->hasAccess('add_categories') ||
                $user->hasAccess('edit_categories') ||
                $user->hasAccess('delete_categories') ||
                $user->hasAccess('view_products') ||
                $user->hasAccess('add_products') ||
                $user->hasAccess('edit_products') ||
                $user->hasAccess('delete_products') ||
                $user->hasAccess('view_inventories') ||
                $user->hasAccess('add_inventories') ||
                $user->hasAccess('edit_inventories') ||
                $user->hasAccess('delete_inventories');
        });

    }
}

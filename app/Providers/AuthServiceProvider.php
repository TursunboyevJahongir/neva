<?php

namespace App\Providers;

use Illuminate\Auth\Access\Response;
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
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        Gate::before(function ($user, $ability) {
            if ($user->admin()) {
                return true;
            }
        });
        $this->registerPolicies();
        Gate::define('view', function ($user, $type) {
            return !empty($user->role->permissions[$type]['view']) && $user->role->permissions[$type]['view']//unnecessary condition, just to be clear
                ? Response::allow()
                : Response::deny('Вы не можете совершить это действие.', 403);
        });

        Gate::define('create', function ($user, $type) {
            return !empty($user->role->permissions[$type]['create']) && $user->role->permissions[$type]['create']//unnecessary condition, just to be clear
                ? Response::allow()
                : Response::deny('Вы не можете совершить это действие.', 403);
        });

        Gate::define('update', function ($user, $type) {
            return !empty($user->role->permissions[$type]['update']) && $user->role->permissions[$type]['update']//unnecessary condition, just to be clear
                ? Response::allow()
                : Response::deny('Вы не можете совершить это действие.', 403);
        });

        Gate::define('delete', function ($user, $type) {
            return !empty($user->role->permissions[$type]['delete']) && $user->role->permissions[$type]['delete']//unnecessary condition, just to be clear
                ? Response::allow()
                : Response::deny('Вы не можете совершить это действие.', 403);
        });
    }
}

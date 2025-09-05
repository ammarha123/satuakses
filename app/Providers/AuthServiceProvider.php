<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    
    protected $policies = [
       
    ];

    public function boot(): void
    {
        Gate::define('menu-admin-only', function ($user) {
            return method_exists($user, 'hasRole') ? $user->hasRole('admin') : ($user->role ?? null) === 'admin';
        });

        Gate::define('menu-employer-only', function ($user) {
            return method_exists($user, 'hasRole') ? $user->hasRole('employer') : ($user->role ?? null) === 'employer';
        });

        Gate::define('menu-company', fn($u) => method_exists($u,'hasRole') ? $u->hasRole('admin') : (($u->role ?? null)==='admin'));
        Gate::define('menu-user', fn($u) => method_exists($u,'hasRole') ? $u->hasRole('admin') : (($u->role ?? null)==='admin'));
    }
}

<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Gate::define('manage-kits', function ($user) {
            return $user->role === 'asisten_lab';
        });

        Gate::define('borrow-kits', function ($user) {
            return $user->role === 'mahasiswa';
        });

        Gate::define('manage-borrowings', function ($user) {
            return $user->role === 'asisten_lab';
        });

        Gate::define('manage-reports', function ($user) {
            return $user->role === 'asisten_lab';
        });
    }
}

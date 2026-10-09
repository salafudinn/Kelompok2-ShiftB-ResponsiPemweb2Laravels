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
            return in_array($user->role, ['asisten_lab', 'owner'], true);
        });

        Gate::define('borrow-kits', function ($user) {
            return $user->role === 'mahasiswa';
        });

        Gate::define('manage-borrowings', function ($user) {
            return in_array($user->role, ['asisten_lab', 'owner'], true);
        });

        Gate::define('manage-reports', function ($user) {
            return in_array($user->role, ['asisten_lab', 'owner'], true);
        });

        Gate::define('manage-assistant-accounts', function ($user) {
            return $user->role === 'owner';
        });
    }
}

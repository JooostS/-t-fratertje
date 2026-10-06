<?php

namespace App\Providers;

use App\Models\User;
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

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('beheer-lidsoorten', fn (User $user) => $user->isBeheerder());
        Gate::define('beheer-gebruikers', fn (User $user) => $user->isBeheerder());
    }
}

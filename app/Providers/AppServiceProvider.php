<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Player;
use App\Models\Club;
use App\Observers\PlayerObserver;
use App\Observers\ClubObserver;

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
        // Register model observers for automatic cache clearing
        Player::observe(PlayerObserver::class);
        Club::observe(ClubObserver::class);
    }
}

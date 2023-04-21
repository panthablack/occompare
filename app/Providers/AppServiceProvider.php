<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\OccupationParser;
use App\Services\OnetOccupationParser;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(OccupationParser::class, OnetOccupationParser::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}

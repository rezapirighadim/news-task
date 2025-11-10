<?php

namespace App\Providers;

use App\Adapters\GuardianAdapter;
use App\Adapters\NewsApiAdapter;
use App\Adapters\NYTimesAdapter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(NewsApiAdapter::class);
        $this->app->singleton(GuardianAdapter::class);
        $this->app->singleton(NYTimesAdapter::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}

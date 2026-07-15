<?php

namespace App\Providers;

use App\Services\Contracts\StockServiceInterface;
use App\Services\StockService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * Binding the StockServiceInterface to its concrete implementation via the
     * service container. This means any class that type-hints StockServiceInterface
     * in its constructor will automatically receive a StockService instance.
     * Swapping to a different implementation (e.g., for testing) requires
     * only changing this binding — zero changes in controllers.
     */
    public function register(): void
    {
        $this->app->bind(
            StockServiceInterface::class,
            StockService::class,
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}

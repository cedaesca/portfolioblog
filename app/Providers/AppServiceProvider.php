<?php

namespace App\Providers;

use App\Interfaces\Services\PostServiceInterface;
use App\Services\PostService;
use Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\Sanctum;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $isProduction = $this->app->environment('production');

        Sanctum::ignoreMigrations();

        if (!$isProduction) {
            $this->app->register(IdeHelperServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->app->bind(PostServiceInterface::class, PostService::class);
    }
}

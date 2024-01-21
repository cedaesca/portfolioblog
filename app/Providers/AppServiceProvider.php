<?php

namespace App\Providers;

use App\Interfaces\Services\PostServiceInterface;
use App\Services\PostService;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\Sanctum;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        Sanctum::ignoreMigrations();
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->app->bind(PostServiceInterface::class, PostService::class);
    }
}

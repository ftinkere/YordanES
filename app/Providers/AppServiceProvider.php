<?php

namespace App\Providers;

use App\Models\Language;
use App\Policies\LanguagePolicy;
use App\Services\FileService;
use App\Services\UserService;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(UserService::class, static function ($app) {
            return new UserService;
        });
        $this->app->singleton(FileService::class, static function ($app) {
            return new FileService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Language::class, LanguagePolicy::class);
    }
}

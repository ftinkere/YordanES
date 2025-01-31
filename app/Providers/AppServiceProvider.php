<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\Language;
use App\Policies\LanguagePolicy;
use App\Services\FileService;
use App\Services\UserService;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Support\ServiceProvider;
use Override;

class AppServiceProvider extends ServiceProvider
{
    public function __construct(private readonly Gate $gate)
    {
    }
    /**
     * Register any application services.
     */
    #[Override]
    public function register(): void
    {
        $this->app->singleton(UserService::class, static fn($app): UserService => new UserService);
        $this->app->singleton(FileService::class, static fn($app): FileService => new FileService());
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->gate->policy(Language::class, LanguagePolicy::class);
    }
}

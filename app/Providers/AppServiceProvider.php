<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\Language;
use App\Policies\LanguagePolicy;
use App\Services\FileService;
use App\Services\UserService;
use Illuminate\Auth\AuthManager;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Factory;
use Override;

class AppServiceProvider extends ServiceProvider
{
    private Gate $gate;

    /**
     * @throws BindingResolutionException
     */
    public function __construct(protected $app)
    {
        parent::__construct($app);
        $this->gate = $this->app->make(Gate::class);
    }
    /**
     * Register any application services.
     */
    #[Override]
    public function register(): void
    {
        $this->app->singleton(UserService::class, fn($app): UserService => new UserService(
            $this->app->make(Factory::class),
            $this->app->make(Hasher::class),
            $this->app->make(AuthManager::class),
        ));
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

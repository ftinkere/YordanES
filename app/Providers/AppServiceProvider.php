<?php

declare(strict_types=1);

namespace App\Providers;

use App\Aggregates\ArticleAggregate;
use App\Aggregates\DictionaryAggregate;
use App\Aggregates\LanguageAggregate;
use App\Aggregates\UserAggregate;
use App\Aggregates\UserRepositoryAggregate;
use App\Models\Language;
use App\Policies\LanguagePolicy;
use App\Services\contracts\RandomInterface;
use App\Services\FileService;
use App\Services\RandomService;
use App\Services\UserService;
use Illuminate\Auth\AuthManager;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Factory;
use Override;
use Ramsey\Uuid\UuidFactory;
use Ramsey\Uuid\UuidFactoryInterface;

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
        $this->app->singleton(FileService::class, static fn($app): FileService => new FileService);
        $this->app->singleton(RandomInterface::class, static fn($app): RandomService => new RandomService);
        $this->app->singleton(UuidFactoryInterface::class, static fn($app): UuidFactory => new UuidFactory);


        // Агрегаты
        $this->app->bind(UserAggregate::class, static fn($app): UserAggregate => new UserAggregate(
            $app->make(UuidFactoryInterface::class),
            $app->make(RandomInterface::class),
        ));

        // Агрегаты
        $this->app->bind(UserRepositoryAggregate::class, static fn($app): UserRepositoryAggregate => new UserRepositoryAggregate(
            $app->make(UuidFactoryInterface::class),
            $app->make(RandomInterface::class),
        ));

        $this->app->bind(LanguageAggregate::class, static fn($app): LanguageAggregate => new LanguageAggregate(
            $app->make(UuidFactoryInterface::class),
        ));

        $this->app->bind(DictionaryAggregate::class, static fn($app): DictionaryAggregate => new DictionaryAggregate(
            $app->make(UuidFactoryInterface::class),
        ));

        $this->app->bind(ArticleAggregate::class, static fn($app): ArticleAggregate => new ArticleAggregate(
            $app->make(UuidFactoryInterface::class),
        ));
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->gate->policy(Language::class, LanguagePolicy::class);
    }
}

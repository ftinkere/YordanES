<?php

declare(strict_types=1);

namespace App\Providers;

use App\Aggregates\ArticleAggregate;
use App\Aggregates\DictionaryAggregate;
use App\Aggregates\LanguageAggregate;
use App\Aggregates\UserAggregate;
use App\Aggregates\UserRepositoryAggregate;
use App\Models\DictionaryArticle;
use App\Models\Language;
use App\Policies\ArticlePolicy;
use App\Policies\LanguagePolicy;
use App\Services\contracts\RandomInterface;
use App\Services\FileService;
use App\Services\RandomService;
use App\Services\UserService;
use Illuminate\Auth\AuthServiceProvider;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\ServiceProvider;
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

        $this->app->register(AuthServiceProvider::class);

        $this->gate = $this->app->make(Gate::class);
    }
    /**
     * Register any application services.
     */
    #[Override]
    public function register(): void
    {
        $this->app->singleton(FileService::class, static fn($app): FileService => new FileService);
        $this->app->singleton(RandomInterface::class, static fn($app): RandomService => new RandomService);
        $this->app->singleton(UuidFactoryInterface::class, static fn($app): UuidFactory => new UuidFactory);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->gate->policy(Language::class, LanguagePolicy::class);
        $this->gate->policy(DictionaryArticle::class, ArticlePolicy::class);
    }
}

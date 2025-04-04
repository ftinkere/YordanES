<?php

declare(strict_types=1);

use App\Providers\AppServiceProvider;
use App\Providers\HorizonServiceProvider;
use Barryvdh\Debugbar\ServiceProvider;
use Sopamo\LaravelFilepond\LaravelFilepondServiceProvider;

return [
    AppServiceProvider::class,
    ServiceProvider::class,
    HorizonServiceProvider::class,
    LaravelFilepondServiceProvider::class,
];

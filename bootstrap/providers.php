<?php

declare(strict_types=1);

use App\Providers\AppServiceProvider;
use App\Providers\HorizonServiceProvider;
use Barryvdh\Debugbar\ServiceProvider;

return [
    AppServiceProvider::class,
    ServiceProvider::class,
    HorizonServiceProvider::class,
];

<?php

declare(strict_types=1);

namespace App\Events\User;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class UserVerifiedEmail extends ShouldBeStored
{
    public function __construct(
        public string $uuid,
    ) {}
}

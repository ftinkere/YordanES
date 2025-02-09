<?php

namespace App\Events\User;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class UserNewRememberToken extends ShouldBeStored
{
    public function __construct(
        public string $uuid,
        public string $token,
    ) {}
}

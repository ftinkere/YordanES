<?php

namespace App\Events\User;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class UserLoggedIn extends ShouldBeStored
{
    public function __construct(
        public string $ulid
    ) {}
}

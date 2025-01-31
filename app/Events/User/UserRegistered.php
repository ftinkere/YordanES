<?php

declare(strict_types=1);

namespace App\Events\User;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class UserRegistered extends ShouldBeStored
{
    public function __construct(
        public string $uuid,
        public string $username,
        public string $name,
        public string $email,
        public string $password_hash,
        public string $remember_token,
    ) {}
}

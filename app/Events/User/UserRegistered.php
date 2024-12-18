<?php

namespace App\Events\User;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class UserRegistered extends ShouldBeStored
{
    public function __construct(
        public string $ulid,
        public string $username,
        public string $visible_name,
        public string $email,
        public string $password_hash,
        public string $remember_token,
    ) {
        echo '';
    }
}

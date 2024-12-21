<?php

namespace App\Events\User;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class UserPasswordResetted extends ShouldBeStored
{
    public function __construct(
        public string $user_uuid,
        public string $password_hash
    ) {}
}
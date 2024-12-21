<?php

namespace App\Events\User;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class UserNotUniqueRegisterAttempted extends ShouldBeStored
{

    public function __construct(
        public string $username,
        public string $visible_name,
        public string $email,
    ) {}
}
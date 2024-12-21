<?php

namespace App\Events\User;

class UserInvalidLoginAttempt extends \Spatie\EventSourcing\StoredEvents\ShouldBeStored
{
    public function __construct(
        public string $uuid,
    ) {}
}
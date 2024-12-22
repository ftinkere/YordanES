<?php

namespace App\Events\User;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class UserEmailChanged extends ShouldBeStored
{
    public function __construct(
        public string $uuid,
        public string $old_email,
        public string $new_email,
    ) {}
}
<?php

namespace App\Events\User;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class UserUsernameChanged extends ShouldBeStored
{
    public function __construct(
        public string $uuid,
        public string $old_username,
        public string $new_username,
    ) {}
}

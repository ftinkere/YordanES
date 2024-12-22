<?php

namespace App\Events\User;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class UserNameChanged extends ShouldBeStored
{
    public function __construct(
        public string $uuid,
        public string $old_name,
        public string $new_name,
    ) {}
}

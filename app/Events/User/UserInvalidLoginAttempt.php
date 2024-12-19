<?php

namespace App\Events\User;

use Illuminate\Support\Carbon;

class UserInvalidLoginAttempt extends \Spatie\EventSourcing\StoredEvents\ShouldBeStored
{
    public function __construct(
        public string $uuid,
        public Carbon $datetime,
    ) {}
}
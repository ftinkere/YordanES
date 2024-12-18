<?php

namespace App\Events;

use Illuminate\Support\Carbon;

class UserInvalidLoginAttempt extends \Spatie\EventSourcing\StoredEvents\ShouldBeStored
{
    public function __construct(
        public string $ulid,
        public Carbon $datetime,
    ) {}
}
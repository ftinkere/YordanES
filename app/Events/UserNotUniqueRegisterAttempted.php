<?php

namespace App\Events;

use Carbon\Carbon;
use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class UserNotUniqueRegisterAttempted extends ShouldBeStored
{

    public function __construct(
        public string $username,
        public string $visible_name,
        public string $email,
        public ?Carbon $datetime = null,
    ) {}
}
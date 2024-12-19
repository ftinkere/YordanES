<?php

namespace App\Events\User;

use Carbon\CarbonInterface;
use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class UserNewRememberToken extends ShouldBeStored
{
    public function __construct(
        public string $user_uuid,
        public string $token,
        public CarbonInterface $datetime,
    ) {}
}

<?php

namespace App\Events\User;

use Illuminate\Support\Carbon;
use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class UserVerifiedEmail extends ShouldBeStored
{
    public function __construct(
        public string $ulid,
        public Carbon $verifiedAt,
    ) {}
}

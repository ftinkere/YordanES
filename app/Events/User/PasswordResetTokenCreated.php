<?php

namespace App\Events\User;

use Illuminate\Support\Carbon;
use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class PasswordResetTokenCreated extends ShouldBeStored
{
    public function __construct(
        public string $user_ulid,
        public string $reset_token,
        public Carbon $created_at,
    ) {}
}

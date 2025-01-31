<?php

declare(strict_types=1);

namespace App\Events\User;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class PasswordResetTokenCreated extends ShouldBeStored
{
    public function __construct(
        public string $user_uuid,
        public string $reset_token,
    ) {}
}

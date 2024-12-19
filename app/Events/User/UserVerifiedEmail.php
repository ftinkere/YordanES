<?php

namespace App\Events\User;

use Carbon\CarbonInterface;
use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class UserVerifiedEmail extends ShouldBeStored
{
    public function __construct(
        public string $uuid,
        public CarbonInterface $verifiedAt,
    ) {}
}

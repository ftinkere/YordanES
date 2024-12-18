<?php

namespace App\Events\User;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class UserForgotPassword extends ShouldBeStored
{
    public function __construct(
        public string $ulid
    ) {
        $this->setAggregateRootUuid($this->ulid);
    }
}

<?php

namespace App\Events\User;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class UserLoggedOut extends ShouldBeStored
{
    public function __construct(
        public string $ulid
    ) {}
    // Всё ещё думаю надо ли сюда добавлять ремембер токен, хм...
}

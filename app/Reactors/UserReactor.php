<?php

namespace App\Reactors;

use App\Events\User\UserLoggedIn;
use Illuminate\Support\Facades\Auth;
use Spatie\EventSourcing\EventHandlers\Reactors\Reactor;

class UserReactor extends Reactor
{
    public function onUserLoggedIn(UserLoggedIn $event): void
    {
        Auth::loginUsingId($event->ulid);
    }
}

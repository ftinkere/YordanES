<?php

namespace App\Reactors;

use App\Events\User\UserLoggedIn;
use App\Events\User\UserLoggedOut;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Spatie\EventSourcing\EventHandlers\Reactors\Reactor;

class UserReactor extends Reactor
{
    public function onUserLoggedIn(UserLoggedIn $event): void
    {
        Auth::loginUsingId($event->ulid);
    }

    public function onUserLoggedOut(UserLoggedOut $event): void
    {
        $user = User::getByUlid($event->ulid);
        if (! $user) {
            return;
        }

        if ($user->ulid === Auth::user()?->ulid) {
            Auth::logout();
        }
    }
}

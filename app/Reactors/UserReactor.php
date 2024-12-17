<?php

namespace App\Reactors;

use App\Events\User\UserForgotPassword;
use App\Events\User\UserLoggedIn;
use App\Events\User\UserLoggedOut;
use App\Jobs\SendMail;
use App\Mail\ForgotPasswordMail;
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

    public function onUserForgotPassword(UserForgotPassword $event): void
    {
        $user = User::getByUlid($event->ulid);
        if (! $user) {
            return;
        }
        $token = $user->createPasswordResetToken();
        if (! $token) {
            abort(500, 'Ошибка создания токена');
        }
        $link = "/reset-password/{$user->ulid}?token={$token->reset_token}";

        SendMail::dispatch($user->email, new ForgotPasswordMail(
            $user->visible_name,
            $link,
        ));
    }
}

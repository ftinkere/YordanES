<?php

namespace App\Reactors;

use App\Aggregates\UserAggregate;
use App\Events\User\UserForgotPassword;
use App\Jobs\SendMail;
use App\Mail\ForgotPasswordMail;
use App\Models\User;
use Spatie\EventSourcing\EventHandlers\Reactors\Reactor;

class UserReactor extends Reactor
{
    public function onUserForgotPassword(UserForgotPassword $event): void
    {

        $user = User::getByUuid($event->uuid);
        if (! $user) {
            return;
        }
        $userAggregate = UserAggregate::retrieve($event->uuid);

        $token = $userAggregate
            ->createPasswordResetToken()
            ->persist()
            ->reset_password_token;
        if (! $token) {
            abort(500, 'Ошибка создания токена');
        }
        $link = "/reset-password/{$user->uuid}?token={$token}";

        SendMail::dispatch($user->email, new ForgotPasswordMail(
            $user->visible_name,
            $link,
        ));
    }
}

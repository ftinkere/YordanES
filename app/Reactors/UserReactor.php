<?php

namespace App\Reactors;

use App\Aggregates\UserAggregate;
use App\Events\User\UserForgotPassword;
use App\Events\User\UserRegistered;
use App\Jobs\SendMail;
use App\Mail\ForgotPasswordMail;
use App\Mail\PasswordResettedMail;
use App\Models\User;
use App\Services\UserService;
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

    public function onUserRegistered(UserRegistered $event, UserService $service): void
    {
        $user = User::getByUuid($event->uuid);
        if (! $user) {
            return;
        }
        $service->sendConfirmationEmail($user);
    }
}

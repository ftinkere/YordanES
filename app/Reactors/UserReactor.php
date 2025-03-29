<?php

declare(strict_types=1);

namespace App\Reactors;

use App\Aggregates\UserAggregate;
use App\Events\User\UserEmailChanged;
use App\Events\User\UserForgotPassword;
use App\Events\User\UserRegistered;
use App\Jobs\SendMail;
use App\Mail\ForgotPasswordMail;
use App\Models\User;
use App\Services\UserService;
use Spatie\EventSourcing\EventHandlers\Reactors\Reactor;

class UserReactor extends Reactor
{
    public function onUserForgotPassword(UserForgotPassword $userForgotPassword): void
    {

        $user = User::getByUuid($userForgotPassword->uuid);
        if (!$user instanceof User) {
            return;
        }

        $userAggregate = UserAggregate::retrieve($userForgotPassword->uuid);

        $token = $userAggregate
            ->createPasswordResetToken()
            ->persist()
            ->reset_password_token;
        if ($token === '' || $token === '0') {
            abort(500, 'Ошибка создания токена');
        }

        $link = sprintf('/reset-password/%s?token=%s', $user->uuid, $token);

        SendMail::dispatch($user->email, new ForgotPasswordMail(
            $user->name,
            $link,
        ));
    }

    public function onUserRegistered(UserRegistered $userRegistered, UserService $userService): void
    {
        $user = User::getByUuid($userRegistered->uuid);
        if (!$user instanceof User) {
            return;
        }

        $userService->sendConfirmationEmail($user);
    }

    public function onUserEmailChanged(UserEmailChanged $userEmailChanged, UserService $userService): void
    {
        $user = User::getByUuid($userEmailChanged->uuid);
        if (!$user instanceof User) {
            return;
        }

        $userService->sendConfirmationEmail($user);
    }
}

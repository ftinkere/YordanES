<?php

namespace App\Listeners;

use App\Events\UserForgotPassword;
use App\Jobs\SendMail;
use App\Mail\ForgotPasswordMail;
use App\Models\User;

class SendPasswordResetNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(UserForgotPassword $event): void
    {
        $user = User::getByUuid($event->uuid);
        if (! $user instanceof User) {
            return;
        }

        $token = $user->createPasswordResetToken()->reset_token;
        if ($token === '' || $token === '0') {
            abort(500, 'Ошибка создания токена');
        }

        $link = sprintf('/reset-password/%s?token=%s', $user->uuid, $token);

        SendMail::dispatch($user->email, new ForgotPasswordMail(
            $user->name,
            $link,
        ));
    }
}

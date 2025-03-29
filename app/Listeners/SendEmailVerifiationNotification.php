<?php

namespace App\Listeners;

use App\Events\UserEmailChanged;
use App\Events\UserRegistered;
use App\Jobs\SendMail;
use App\Mail\EmailConfirmationMail;
use App\Models\User;

class SendEmailVerifiationNotification
{
    public function __construct() {}

    public function handle(UserEmailChanged|UserRegistered $event): void
    {
        $user = User::getByUuid($event->uuid);
        if (! $user instanceof User) {
            return;
        }

        // TODO: пока очень не очень, но сойдёт
        $link = '/confirm-email/' . $user->uuid;

        SendMail::dispatch($user->email, new EmailConfirmationMail(
            $user->name,
            $link,
        ));
    }
}

<?php

namespace App\Projectors;

use App\Events\User\PasswordResetTokenCreated;
use App\Events\User\UserRegistered;
use App\Events\User\UserVerifiedEmail;
use App\Models\PasswordResetToken;
use App\Models\User;
use Illuminate\Support\Str;
use Spatie\EventSourcing\EventHandlers\Projectors\Projector;

class UserProjector extends Projector
{
    public function onUserRegistered(UserRegistered $event): void
    {
        $user = new User([
            'ulid' => $event->ulid,
            'username' => $event->username,
            'visible_name' => $event->visible_name,
            'email' => $event->email,
            'password_hash' => $event->password_hash,
        ]);
        // При реплае евентов будет новый токен.
        // TODO: поправить
        $user->setRememberToken(Str::random(60));
        $user->writeable()->save();
    }

    public function onUserVerifiedEmail(UserVerifiedEmail $event): void
    {
        $user = User::getByUlid($event->ulid);
        $user->email_verified_at = $event->verifiedAt;
        $user->writeable()->save();
    }

    public function onPasswordResetTokenCreated(PasswordResetTokenCreated $event): void
    {
        $token = PasswordResetToken::where('user_ulid', $event->user_ulid)->first();
        if (! $token) {
            $token = new PasswordResetToken;
        }
        $token->user_ulid = $event->user_ulid;
        $token->reset_token = $event->reset_token;
        $token->created_at = $event->created_at;
        $token->writeable()->save();
    }
}

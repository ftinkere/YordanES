<?php

namespace App\Projectors;

use App\Events\User\PasswordResetTokenCreated;
use App\Events\User\UserNewRememberToken;
use App\Events\User\UserRegistered;
use App\Events\User\UserVerifiedEmail;
use App\Models\PasswordResetToken;
use App\Models\User;
use Spatie\EventSourcing\EventHandlers\Projectors\Projector;

class UserProjector extends Projector
{
    public function onUserRegistered(UserRegistered $event): void
    {
        $user = new User();
        $user->uuid = $event->uuid;
        $user->username = $event->username;
        $user->visible_name = $event->visible_name;
        $user->email = $event->email;
        $user->password_hash = $event->password_hash;
        $user->remember_token = $event->remember_token;
        $user->created_at = $event->createdAt();
        $user->updated_at = $event->createdAt();
        $user->writeable()->save();
    }

    public function onUserVerifiedEmail(UserVerifiedEmail $event): void
    {
        $user = User::getByUuid($event->uuid);
        $user->email_verified_at = $event->verifiedAt;
        $user->updated_at = $event->createdAt();
        $user->writeable()->save();
    }

    public function onPasswordResetTokenCreated(PasswordResetTokenCreated $event): void
    {
        $token = PasswordResetToken::where('user_uuid', $event->user_uuid)->first();
        if (! $token) {
            $token = new PasswordResetToken;
        }
        $token->user_uuid = $event->user_uuid;
        $token->reset_token = $event->reset_token;
        $token->created_at = $event->createdAt();
        $token->writeable()->save();
    }

    public function onUserNewRememberToken(UserNewRememberToken $event): void
    {
        $user = User::getByUuid($event->user_uuid);
        $user->remember_token = $event->token;
        $user->writeable()->save();
    }
}

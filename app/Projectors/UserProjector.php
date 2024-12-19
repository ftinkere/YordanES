<?php

namespace App\Projectors;

use App\Events\User\PasswordResetTokenCreated;
use App\Events\User\UserRegistered;
use App\Events\User\UserVerifiedEmail;
use App\Models\PasswordResetToken;
use App\Models\User;
use Spatie\EventSourcing\EventHandlers\Projectors\Projector;

class UserProjector extends Projector
{
    public function onUserRegistered(UserRegistered $event): void
    {
        $user = new User([
            'uuid' => $event->uuid,
            'username' => $event->username,
            'visible_name' => $event->visible_name,
            'email' => $event->email,
            'password_hash' => $event->password_hash,
            'remember_token' => $event->remember_token,
        ]);
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
}

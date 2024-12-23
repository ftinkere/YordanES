<?php

namespace App\Projectors;

use App\Events\User\PasswordResetTokenCreated;
use App\Events\User\UserEmailChanged;
use App\Events\User\UserNameChanged;
use App\Events\User\UserNewRememberToken;
use App\Events\User\UserPasswordResetted;
use App\Events\User\UserRegistered;
use App\Events\User\UserSettedAvatar;
use App\Events\User\UserUsernameChanged;
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
        $user->name = $event->name;
        $user->email = $event->email;
        $user->password_hash = $event->password_hash;
        $user->remember_token = $event->remember_token;
        $user->created_at = $event->createdAt()->timezone(config('app.timezone'));
        $user->updated_at = $event->createdAt()->timezone(config('app.timezone'));
        $user->writeable()->save();
    }

    public function onUserVerifiedEmail(UserVerifiedEmail $event): void
    {
        $user = User::getByUuid($event->uuid);
        $user->email_verified_at = $event->createdAt()->timezone(config('app.timezone'));
        $user->updated_at = $event->createdAt()->timezone(config('app.timezone'));
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
        $token->created_at = $event->createdAt()->timezone(config('app.timezone'));
        $token->writeable()->save();
    }

    public function onUserNewRememberToken(UserNewRememberToken $event): void
    {
        $user = User::getByUuid($event->uuid);
        $user->remember_token = $event->token;
        $user->updated_at = $event->createdAt()->timezone(config('app.timezone'));
        $user->writeable()->save();
    }

    public function onUserPasswordResetted(UserPasswordResetted $event): void
    {
        $user = User::getByUuid($event->uuid);
        $user->password_hash = $event->password_hash;
        $user->updated_at = $event->createdAt()->timezone(config('app.timezone'));
        $user->writeable()->save();
    }

    public function onUserUsernameChanged(UserUsernameChanged $event): void
    {
        $user = User::getByUuid($event->uuid);
        $user->username = $event->new_username;
        $user->writeable()->save();
    }

    public function onUserNameChanged(UserNameChanged $event): void
    {
        $user = User::getByUuid($event->uuid);
        $user->name = $event->new_name;
        $user->writeable()->save();
    }

    public function onUserEmailChanged(UserEmailChanged $event): void
    {
        $user = User::getByUuid($event->uuid);
        $user->email = $event->new_email;
        $user->email_verified_at = null;
        $user->writeable()->save();
    }

    public function onUserSettedAvatar(UserSettedAvatar $event): void
    {

        $user = User::getByUuid($event->uuid);
        $user->avatar = $event->path;
        $user->writeable()->save();
    }
}

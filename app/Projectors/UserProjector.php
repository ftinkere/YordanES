<?php

declare(strict_types=1);

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
use Illuminate\Contracts\Config\Repository;
use Spatie\EventSourcing\EventHandlers\Projectors\Projector;

class UserProjector extends Projector
{
    public function __construct(private readonly Repository $configRepository)
    {
    }
    public function onUserRegistered(UserRegistered $userRegistered): void
    {
        $user = new User();
        $user->uuid = $userRegistered->uuid;
        $user->username = $userRegistered->username;
        $user->name = $userRegistered->name;
        $user->email = $userRegistered->email;
        $user->password_hash = $userRegistered->password_hash;
        $user->remember_token = $userRegistered->remember_token;
        $user->created_at = $userRegistered->createdAt()->timezone($this->configRepository->get('app.timezone'));
        $user->updated_at = $userRegistered->createdAt()->timezone($this->configRepository->get('app.timezone'));
        $user->writeable()->save();
    }

    public function onUserVerifiedEmail(UserVerifiedEmail $userVerifiedEmail): void
    {
        $user = User::getByUuid($userVerifiedEmail->uuid);
        $user->email_verified_at = $userVerifiedEmail->createdAt()->timezone($this->configRepository->get('app.timezone'));
        $user->updated_at = $userVerifiedEmail->createdAt()->timezone($this->configRepository->get('app.timezone'));
        $user->writeable()->save();
    }

    public function onPasswordResetTokenCreated(PasswordResetTokenCreated $passwordResetTokenCreated): void
    {
        $token = PasswordResetToken::where('user_uuid', $passwordResetTokenCreated->user_uuid)->first();
        if (! $token) {
            $token = new PasswordResetToken;
        }

        $token->user_uuid = $passwordResetTokenCreated->user_uuid;
        $token->reset_token = $passwordResetTokenCreated->reset_token;
        $token->created_at = $passwordResetTokenCreated->createdAt()->timezone($this->configRepository->get('app.timezone'));
        $token->writeable()->save();
    }

    public function onUserNewRememberToken(UserNewRememberToken $userNewRememberToken): void
    {
        $user = User::getByUuid($userNewRememberToken->uuid);
        $user->remember_token = $userNewRememberToken->token;
        $user->updated_at = $userNewRememberToken->createdAt()->timezone($this->configRepository->get('app.timezone'));
        $user->writeable()->save();
    }

    public function onUserPasswordResetted(UserPasswordResetted $userPasswordResetted): void
    {
        $user = User::getByUuid($userPasswordResetted->uuid);
        $user->password_hash = $userPasswordResetted->password_hash;
        $user->updated_at = $userPasswordResetted->createdAt()->timezone($this->configRepository->get('app.timezone'));
        $user->writeable()->save();
    }

    public function onUserUsernameChanged(UserUsernameChanged $userUsernameChanged): void
    {
        $user = User::getByUuid($userUsernameChanged->uuid);
        $user->username = $userUsernameChanged->new_username;
        $user->writeable()->save();
    }

    public function onUserNameChanged(UserNameChanged $userNameChanged): void
    {
        $user = User::getByUuid($userNameChanged->uuid);
        $user->name = $userNameChanged->new_name;
        $user->writeable()->save();
    }

    public function onUserEmailChanged(UserEmailChanged $userEmailChanged): void
    {
        $user = User::getByUuid($userEmailChanged->uuid);
        $user->email = $userEmailChanged->new_email;
        $user->email_verified_at = null;
        $user->writeable()->save();
    }

    public function onUserSettedAvatar(UserSettedAvatar $userSettedAvatar): void
    {

        $user = User::getByUuid($userSettedAvatar->uuid);
        $user->avatar = $userSettedAvatar->path;
        $user->writeable()->save();
    }
}

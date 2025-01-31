<?php

declare(strict_types=1);

namespace App\Aggregates;

use App\Events\User\PasswordResetTokenCreated;
use App\Events\User\UserEmailChanged;
use App\Events\User\UserInvalidLoginAttempt;
use App\Events\User\UserLoggedIn;
use App\Events\User\UserLoggedOut;
use App\Events\User\UserNameChanged;
use App\Events\User\UserNewRememberToken;
use App\Events\User\UserNotUniqueRegisterAttempted;
use App\Events\User\UserPasswordResetted;
use App\Events\User\UserRegistered;
use App\Events\User\UserSettedAvatar;
use App\Events\User\UserUsernameChanged;
use App\Events\User\UserVerifiedEmail;
use Carbon\CarbonInterface;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;
use SensitiveParameter;
use Spatie\EventSourcing\AggregateRoots\AggregateRoot;

class UserAggregate extends AggregateRoot
{
    public string $user_uuid;

    public string $username;

    public string $name;

    public string $email;

    public string $avatar;

    public string $password_hash;

    public string $remember_token;

    public ?CarbonInterface $email_verified_at = null;

    public string $reset_password_token;

    public CarbonInterface $reset_password_token_created_at;


    protected mixed $uuidGenerate = [Uuid::class, 'uuid7'];

    protected mixed $tokenGenerate = [Str::class, 'random'];

    public function __construct(private readonly Hasher $hasher)
    {
    }

    public function withGenerators(mixed $uuidGenerate, mixed $tokenGenerate): self
    {
        $this->uuidGenerate = $uuidGenerate;
        $this->tokenGenerate = $tokenGenerate;

        return $this;
    }

    // Не вызывать без проверки на уникальность $username и $email
    // Пока что проверка только по query базе
    public function register(string $username, string $name, string $email, string $password_hash): ?self
    {
        $uuid = call_user_func($this->uuidGenerate);
        $remember_token = call_user_func($this->tokenGenerate, 60);

        if (! is_string($uuid)) {
            $uuid = $uuid->toString();
        }

        $this->loadUuid($uuid);

        $this->recordThat(new UserRegistered($uuid, $username, $name, $email, $password_hash, $remember_token));

        return $this;
    }

    public function applyUserRegistered(UserRegistered $userRegistered): void
    {
        $this->user_uuid = $userRegistered->uuid;
        $this->username = $userRegistered->username;
        $this->name = $userRegistered->name;
        $this->email = $userRegistered->email;
        $this->password_hash = $userRegistered->password_hash;
        $this->remember_token = $userRegistered->remember_token;
    }

    public function verifyEmail(?string $token = null): self
    {
        // TODO: поменять на свой токен
        if ($token !== null && $token !== $this->user_uuid) {
            return $this;
        }

        $this->recordThat(new UserVerifiedEmail($this->user_uuid));

        return $this;
    }

    public function applyUserVerifiedEmail(UserVerifiedEmail $userVerifiedEmail): void
    {
        $this->email_verified_at = $userVerifiedEmail->createdAt();
    }

    protected function checkPassword(#[SensitiveParameter] string $password): bool
    {
        return $this->hasher->check($password, $this->password_hash);
    }

    public function login(#[SensitiveParameter] string $password): self
    {
        if (! $this->checkPassword($password)) {
            $this->invalidLoginAttempt();

            return $this;
        }

        $this->recordThat(new UserLoggedIn($this->user_uuid));

        return $this;
    }

    public function invalidLoginAttempt(): self
    {
        $this->recordThat(new UserInvalidLoginAttempt($this->user_uuid));

        return $this;
    }

    public function logout(): self
    {
        $this->recordThat(new UserLoggedOut($this->user_uuid));

        $token = call_user_func($this->tokenGenerate, 60);
        $this->recordThat(new UserNewRememberToken($this->user_uuid, $token));

        return $this;
    }

    public function applyUserNewRememberToken(UserNewRememberToken $userNewRememberToken): void
    {
        $this->remember_token = $userNewRememberToken->token;
    }

    public function createPasswordResetToken(): self
    {
        $token = call_user_func($this->tokenGenerate, 64);

        $this->recordThat(new PasswordResetTokenCreated($this->user_uuid, $token));

        return $this;
    }

    public function applyPasswordResetTokenCreated(PasswordResetTokenCreated $passwordResetTokenCreated): void
    {
        $this->reset_password_token = $passwordResetTokenCreated->reset_token;
        $this->reset_password_token_created_at = $passwordResetTokenCreated->createdAt();
    }

    public function notUniqueRegisterAttempt(string $username, string $visible_name, string $email): self
    {
        $this->recordThat(new UserNotUniqueRegisterAttempted($username, $visible_name, $email));

        return $this;
    }

    public function resetPassword(#[SensitiveParameter] string $password, ?string $token = null): self
    {
        if ($token && ($this->reset_password_token !== $token || $this->reset_password_token_created_at->isLastHour())) {
            return $this;
        }

        $this->recordThat(new UserPasswordResetted($this->user_uuid, $this->hasher->make($password)));

        return $this;
    }

    public function applyUserPasswordResetted(UserPasswordResetted $userPasswordResetted): void
    {
        $this->password_hash = $userPasswordResetted->password_hash;
    }

    public function changeUsername(string $username): self
    {
        if (mb_strlen($username) < 3 || $this->username === $username) {
            return $this;
        }

        $this->recordThat(new UserUsernameChanged($this->user_uuid, old_username: $this->username, new_username:  $username));

        return $this;
    }

    public function applyUserUsernameChanged(UserUsernameChanged $userUsernameChanged): void
    {
        $this->username = $userUsernameChanged->new_username;
    }

    public function changeName(string $name): self
    {
        if ($this->name === $name) {
            return $this;
        }

        $this->recordThat(new UserNameChanged($this->user_uuid, old_name: $this->name, new_name:  $name));

        return $this;
    }

    public function applyUserNameChanged(UserNameChanged $userNameChanged): void
    {
        $this->name = $userNameChanged->new_name;
    }

    public function changeEmail(string $email): self
    {
        // TODO: валидацию почты

        if ($this->email === $email) {
            return $this;
        }

        $this->recordThat(new UserEmailChanged($this->user_uuid, old_email: $this->email, new_email:  $email));

        return $this;
    }

    public function applyUserEmailChanged(UserEmailChanged $userEmailChanged): void
    {
        $this->email = $userEmailChanged->new_email;
        $this->email_verified_at = null;
    }

    public function setAvatar(string $path): self
    {
        $this->recordThat(new UserSettedAvatar($this->user_uuid, $path));

        return $this;
    }

    public function applyUserSettedAvatar(UserSettedAvatar $userSettedAvatar): void
    {
        $this->avatar = $userSettedAvatar->path;
    }
}

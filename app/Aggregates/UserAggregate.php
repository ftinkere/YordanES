<?php

namespace App\Aggregates;

use App\Events\User\PasswordResetTokenCreated;
use App\Events\User\UserInvalidLoginAttempt;
use App\Events\User\UserLoggedIn;
use App\Events\User\UserLoggedOut;
use App\Events\User\UserNotUniqueRegisterAttempted;
use App\Events\User\UserRegistered;
use App\Events\User\UserVerifiedEmail;
use Carbon\CarbonInterface;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
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

    public string $password_hash;

    public string $remember_token;

    public CarbonInterface $email_verified_at;

    public string $reset_password_token;


    protected $uuidGenerate;
    protected $tokenGenerate;
    public function __construct()
    {
        $this->uuidGenerate = [Uuid::class, 'uuid7'];
        $this->tokenGenerate = [Str::class, 'random'];
    }

    public function withGenerators($uuidGenerate, $tokenGenerate): self
    {
        $this->uuidGenerate = $uuidGenerate;
        $this->tokenGenerate = $tokenGenerate;

        return $this;
    }

    // Не вызывать без проверки на уникальность $username и $email
    // Пока что проверка только по query базе
    public function register(string $username, string $visible_name, string $email, string $password_hash): ?self
    {
        $uuid = call_user_func($this->uuidGenerate);
        $remember_token = call_user_func($this->tokenGenerate, 60);

        if (! is_string($uuid)) {
            $uuid = (string)$uuid;
        }

        $this->loadUuid($uuid);

        $this->recordThat(new UserRegistered($uuid, $username, $visible_name, $email, $password_hash, $remember_token));

        return $this;
    }

    public function applyUserRegistered(UserRegistered $event): void
    {
        $this->user_uuid = $event->uuid;
        $this->username = $event->username;
        $this->name = $event->visible_name;
        $this->email = $event->email;
        $this->password_hash = $event->password_hash;
        $this->remember_token = $event->remember_token;
    }

    public function verifyEmail(?Carbon $date = null): self
    {
        $this->recordThat(new UserVerifiedEmail($this->user_uuid, $date ?? new Carbon));

        return $this;
    }

    public function applyUserVerifiedEmail(UserVerifiedEmail $event): void
    {
        $this->email_verified_at = $event->verifiedAt;
    }

    protected function checkPassword(#[SensitiveParameter] string $password): bool
    {
        return Hash::check($password, $this->password_hash);
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

    public function invalidLoginAttempt(?Carbon $datetime = null): self
    {
        $this->recordThat(new UserInvalidLoginAttempt($this->user_uuid, $datetime ?? new Carbon));

        return $this;
    }

    public function logout(): self
    {
        $this->recordThat(new UserLoggedOut($this->user_uuid));

        return $this;
    }

    public function createPasswordResetToken(?Carbon $datetime = null): self
    {
        $token = call_user_func($this->tokenGenerate, 64);

        $this->recordThat(new PasswordResetTokenCreated($this->user_uuid, $token, $datetime ?? new Carbon));

        return $this;
    }

    public function applyPasswordResetTokenCreated(PasswordResetTokenCreated $event): void
    {
        $this->reset_password_token = $event->reset_token;
    }

    public function notUniqueRegisterAttempt(string $username, string $visible_name, string $email, ?Carbon $datetime = null): self
    {
        $this->recordThat(new UserNotUniqueRegisterAttempted($username, $visible_name, $email, $datetime));

        return $this;
    }
}

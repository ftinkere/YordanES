<?php

namespace App\Aggregates;

use App\Events\User\PasswordResetTokenCreated;
use App\Events\User\UserLoggedIn;
use App\Events\User\UserLoggedOut;
use App\Events\User\UserRegistered;
use App\Events\User\UserVerifiedEmail;
use App\Models\StoredEvent;
use Carbon\CarbonInterface;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\EventSourcing\AggregateRoots\AggregateRoot;
use Symfony\Component\Uid\Ulid;

class UserAggregate extends AggregateRoot
{
    protected string $ulid;

    protected string $username;

    protected string $email;

    protected string $password_hash;

    protected string $remember_token;

    protected CarbonInterface $email_verified_at;

    protected string $reset_password_token;


    protected $ulidGenerate;
    protected $tokenGenerate;
    public function __construct()
    {
        $this->ulidGenerate = [Ulid::class, 'generate'];
        $this->tokenGenerate = [Str::class, 'random'];
    }

    public function withGenerators($ulidGenerate, $tokenGenerate): self
    {
        $this->ulidGenerate = $ulidGenerate;
        $this->tokenGenerate = $tokenGenerate;

        return $this;
    }

    public function register(string $username, string $visible_name, string $email, string $password_hash): ?self
    {
        $ulid = call_user_func($this->ulidGenerate);
        $this->loadUuid($ulid);
        $remember_token = call_user_func($this->tokenGenerate, 60);

        if (! $this->checkUniqueUsername($username)) {
            return null;
        }

        $this->recordThat(new UserRegistered($ulid, $username, $visible_name, $email, $password_hash, $remember_token));

        return $this;
    }

    public function applyUserRegistered(UserRegistered $event): void
    {
        $this->ulid = $event->ulid;
        $this->username = $event->username;
        $this->email = $event->email;
        $this->password_hash = $event->password_hash;
        $this->remember_token = $event->remember_token;
    }

    public function verifyEmail(?Carbon $date = null): self
    {
        $this->recordThat(new UserVerifiedEmail($this->ulid, $date ?? new Carbon));

        return $this;
    }

    public function applyUserVerifiedEmail(UserVerifiedEmail $event): void
    {
        $this->email_verified_at = $event->verifiedAt;
    }

    protected function checkPassword(string $password): bool
    {
        return Hash::check($password, $this->password_hash);
    }

    protected function checkUniqueUsername(string $username): bool
    {
        return true;
        // TODO: Понять как прочесть только все нижестоящие события без события
        return ! StoredEvent::where('id', '>', 0)
            ->whereIn('event_class', [
                UserRegistered::class,
                // UserUsernameChanged::class
            ])
            ->where('event_properties.username', $username)
            ->exists();
    }

    public function login(string $username, string $password): ?self
    {
        if (! $this->checkPassword($password)) {
            return null;
        }

        $this->recordThat(new UserLoggedIn($this->ulid));

        return $this;
    }

    public function logout(): self
    {
        $this->recordThat(new UserLoggedOut($this->ulid));

        return $this;
    }

    public function createPasswordResetToken(): self
    {
        $token = Str::random(64);

        $this->recordThat(new PasswordResetTokenCreated($this->ulid, $token, new Carbon));

        return $this;
    }

    public function applyPasswordResetTokenCreated(PasswordResetTokenCreated $event): void
    {
        $this->reset_password_token = $event->reset_token;
    }
}

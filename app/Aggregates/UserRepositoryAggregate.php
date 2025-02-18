<?php

namespace App\Aggregates;

use App\Events\User\UserNotUniqueRegisterAttempted;
use App\Events\User\UserRegistered;
use App\Events\User\UserUsernameChanged;
use App\Services\contracts\RandomInterface;
use DomainException;
use Egulias\EmailValidator\EmailValidator;
use Egulias\EmailValidator\Validation\RFCValidation;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;
use Ramsey\Uuid\UuidFactoryInterface;
use Spatie\EventSourcing\AggregateRoots\AggregateRoot;
use stdClass;

use function is_string;

class UserRepositoryAggregate extends AggregateRoot
{
    public const string UUID = '0194e417-2489-7f2c-b824-9bed59a51ce4';
    public array $users = [];

    public function __construct(
        protected UuidFactoryInterface $uuidFactory,
        protected RandomInterface $random,
    ) {
        $this->loadUuid(self::UUID);
    }

    public static function retrieve(string $uuid = self::UUID): static
    {
        return parent::retrieve(self::UUID);
    }

    public function register(string $username, string $name, string $email, string $password_hash): UserAggregate
    {
        // Чек почты на валидность
        $validator = new EmailValidator();
        if (! $validator->isValid($email, new RFCValidation)) {
            throw new ValidationException($validator);
        }

        // Чек почты и ника на уникальность
        $exist = Arr::where($this->users, function ($value, $key) use ($username, $email) {
            return $value->username === $username || $value->email === $email;
        });

        if ($exist) {
            $this->notUniqueRegisterAttempt($username, $name, $email)->persist();
            throw new DomainException('Пользователь с таким никнеймом или почтой уже существует.');
        }

        $uuid = $this->uuidFactory->uuid7();
        $remember_token = $this->random->randomString(60);

        if (! is_string($uuid)) {
            $uuid = (string) $uuid;
        }

        $user = app(UserAggregate::class);
        $user->loadUuid($uuid);
        $event = new UserRegistered($uuid, $username, $name, $email, $password_hash, $remember_token);
        $this->recordThat($event)->persist();
        $user->recordThat($event);
        $this->snapshot();
        return $user;
    }

    public function applyUserRegistered(UserRegistered $userRegistered): void
    {
        $user = new stdClass;
        $user->uuid = $userRegistered->uuid;
        $user->username = $userRegistered->username;
        $user->email = $userRegistered->email;
        $this->users[$user->uuid] = $user;
    }

    public function notUniqueRegisterAttempt(string $username, string $name, string $email): self
    {
        $this->recordThat(new UserNotUniqueRegisterAttempted($username, $name, $email));

        return $this;
    }

    public function changeUsername(UserAggregate|string $userAggregate, string $username): self
    {
        if (is_string($userAggregate)) {
            $userAggregate = UserAggregate::retrieve($userAggregate);
        }

        if (mb_strlen($username) < 3) {
            throw new DomainException('Никнейм не может быть меньше 3 символов в длину');
        }

        if ($userAggregate->username === $username) {
            return $this;
        }

        $exist = Arr::where($this->users, function ($value, $key) use ($username) {
            return $value['username'] === $username;
        });

        if ($exist) {
            throw new DomainException('Такой никнейм уже существует');
        }

        $event = new UserUsernameChanged($userAggregate->user_uuid, old_username: $userAggregate->username, new_username:  $username);
        $this->recordThat($event);
        $userAggregate->recordThat($event)->persist();
        $this->snapshot();
        return $this;
    }

    public function applyUserUsernameChanged(UserUsernameChanged $userUsernameChanged): void
    {
        foreach ($this->users as &$user) {
            if ($user['username'] === $userUsernameChanged->old_username && $user['uuid'] === $userUsernameChanged->uuid) {
                $user['username'] = $userUsernameChanged->new_username;
            }
        }
    }


    public function user($uuid): UserAggregate
    {
        return UserAggregate::retrieve($uuid);
    }
}

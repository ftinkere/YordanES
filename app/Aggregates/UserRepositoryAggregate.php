<?php

namespace App\Aggregates;

use App\Events\User\UserRegistered;
use App\Services\contracts\RandomInterface;
use DomainException;
use Egulias\EmailValidator\EmailValidator;
use Egulias\EmailValidator\Validation\RFCValidation;
use Illuminate\Support\Arr;
use Ramsey\Uuid\UuidFactoryInterface;
use Spatie\EventSourcing\AggregateRoots\AggregateRoot;
use stdClass;

class UserRepositoryAggregate extends AggregateRoot
{
    public array $users = [];

    public function __construct(
        protected UuidFactoryInterface $uuidFactory,
        protected RandomInterface $random,
    ) {}

    public function register(string $username, string $name, string $email, string $password_hash): UserAggregate
    {
        // Чек почты на валидность
        $validator = new EmailValidator();
        if (! $validator->isValid($email, new RFCValidation)) {
            throw new DomainException($validator->getError()->description());
        }

        // Чек почты и ника на уникальность
        $exist = Arr::where($this->users, function ($value, $key) use ($username, $email) {
            return $value->username === $username || $value->email === $email;
        });

        if ($exist) {
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
        $user->recordThat($event)->persist();
        $this->recordThat($event);
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

    public function user($uuid): UserAggregate
    {
        return UserAggregate::retrieve($uuid);
    }
}

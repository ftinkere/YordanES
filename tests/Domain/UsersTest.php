<?php

use App\Aggregates\UserAggregate;
use App\Aggregates\UserRepositoryAggregate;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Hash;

pest()
    ->extend(Tests\TestCase::class)
    ->use(DatabaseMigrations::class)
;

beforeEach(function () {
   $this->user = new stdClass;
   $this->user->uuid = $this->uuid;
   $this->user->username = 'JohnDoe';
   $this->user->name = 'John Doe';
   $this->user->email = 'john@yordan.ru';
   $this->user->password = 'password';
   $this->user->password_hash = Hash::make('password');
   $this->user->token = $this->token;
});

test('Пользователь регистрируется', function () {
    $repository = UserRepositoryAggregate::fake();

    $repository->when(function (UserRepositoryAggregate $userRepositoryAggregate) {
        return $userRepositoryAggregate
            ->register($this->user->username, $this->user->name, $this->user->email, $this->user->password_hash)
            ->persist()
            ;
    })->then(function (UserAggregate $userAggregate) {
        expect($userAggregate->user_uuid)->toBe($this->user->uuid)
            ->and($userAggregate->username)->toBe($this->user->username)
            ->and($userAggregate->name)->toBe($this->user->name)
            ->and($userAggregate->email)->toBe($this->user->email)
        ;
        $this->userAggregate = $userAggregate;
    });
    $repository->when(function (UserRepositoryAggregate $userRepositoryAggregate) {
        return $userRepositoryAggregate;
    })->then(function (UserRepositoryAggregate $userRepositoryAggregate) {
        $tmp = new stdClass;
        $tmp->uuid = $this->user->uuid;
        $tmp->username = $this->user->username;
        $tmp->email = $this->user->email;

        expect($userRepositoryAggregate->users)
            ->not->toBeEmpty()
            ->and($userRepositoryAggregate->users)->toHaveCount(1)
            ->toContainEqual($tmp);
        ;
    });
});

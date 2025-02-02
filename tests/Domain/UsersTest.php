<?php

use App\Aggregates\UserAggregate;
use App\Aggregates\UserRepositoryAggregate;
use App\Events\User\PasswordResetTokenCreated;
use App\Events\User\UserInvalidLoginAttempt;
use App\Events\User\UserLoggedIn;
use App\Events\User\UserLoggedOut;
use App\Events\User\UserNewRememberToken;
use App\Events\User\UserNotUniqueRegisterAttempted;
use App\Events\User\UserPasswordResetted;
use App\Events\User\UserRegistered;
use App\Events\User\UserUsernameChanged;
use App\Events\User\UserVerifiedEmail;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Hash;
use Ramsey\Uuid\Uuid;
use Spatie\EventSourcing\AggregateRoots\FakeAggregateRoot;

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
            ;
    })->then(function (UserAggregate $userAggregate) {
        expect($userAggregate->user_uuid)->toBe($this->user->uuid)
            ->and($userAggregate->username)->toBe($this->user->username)
            ->and($userAggregate->name)->toBe($this->user->name)
            ->and($userAggregate->email)->toBe($this->user->email)
        ;
        $this->userAggregate = $userAggregate;
    });
    $repository->assertApplied([new UserRegistered(
        $this->user->uuid,
        $this->user->username,
        $this->user->name,
        $this->user->email,
        $this->user->password_hash,
        $this->user->token,
    )]);

    // И заносится в репозиторий
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
            ->toContainEqual($tmp)
        ;
    });
});

test('Пользователь не регистрируется дублем', function () {
    $repository = UserRepositoryAggregate::fake()
        ->given([new UserRegistered(
            $this->user->uuid,
            $this->user->username,
            $this->user->name,
            $this->user->email,
            $this->user->password_hash,
            $this->user->token,
        )]);
    expect(fn() => $repository->when(function (UserRepositoryAggregate $userRepositoryAggregate) {
        return $userRepositoryAggregate
            ->register($this->user->username, $this->user->name, $this->user->email, $this->user->password_hash);
    }),
    )->toThrow(DomainException::class);
    $repository->assertEventRecorded(
        new UserNotUniqueRegisterAttempted($this->user->username, $this->user->name, $this->user->email),
    );
    $repository->assertNotRecorded([UserRegistered::class]);
});

describe('Пользователь', function () {
    beforeEach(function () {
        $this->fakeUser = UserAggregate::fake()
            ->given(
                [
                    new UserRegistered(
                        $this->user->uuid,
                        $this->user->username,
                        $this->user->name,
                        $this->user->email,
                        $this->user->password_hash,
                        $this->user->token,
                    ),
                ],
            );
    });

    test('верифицирует почту', function () {
        /** @var FakeAggregateRoot $fake */
        $fake = $this->fakeUser;
        $fake->when(function (UserAggregate $userAggregate) {
            return $userAggregate->verifyEmail($userAggregate->user_uuid);
        })->then(function (UserAggregate $userAggregate) {
            expect($userAggregate->email_verified_at)->not->toBeNull();
        });
        $fake->assertEventRecorded(new UserVerifiedEmail($this->user->uuid));
    });

    test('не верифицирует почту неправильным токеном', function () {
        /** @var FakeAggregateRoot $fake */
        $fake = $this->fakeUser;
        $fake->when(function (UserAggregate $userAggregate) {
            return $userAggregate->verifyEmail('invalid');
        })->then(function (UserAggregate $userAggregate) {
            expect($userAggregate->email_verified_at)->toBeNull();
        });
        $fake->assertNotRecorded([UserVerifiedEmail::class]);
    });

    test('логинится (событие)', function () {
        /** @var FakeAggregateRoot $fake */
        $fake = $this->fakeUser;
        $fake->when(function (UserAggregate $userAggregate) {
            return $userAggregate->login($this->user->password);
        });
        $fake->assertNotRecorded([UserInvalidLoginAttempt::class]);
        $fake->assertEventRecorded(new UserLoggedIn($this->user->uuid));
    });

    test('логаутится (событие)', function () {
        /** @var FakeAggregateRoot $fake */
        $fake = $this->fakeUser;
        $fake->when(function (UserAggregate $userAggregate) {
            return $userAggregate->logout();
        });
        $fake->assertEventRecorded(new UserLoggedOut($this->user->uuid));
        $fake->assertEventRecorded(new UserNewRememberToken($this->user->uuid, $this->user->token));
    });


    test('создаёт ресет токен', function () {
        /** @var FakeAggregateRoot $fake */
        $fake = $this->fakeUser;
        $fake->when(function (UserAggregate $userAggregate) {
            return $userAggregate->createPasswordResetToken();
        });
        $fake->assertEventRecorded(new PasswordResetTokenCreated($this->user->uuid, $this->user->token));
    });

    test('ресетает пароль', function () {
        $newPassword = $this->user->password . 'new';
        /** @var FakeAggregateRoot $fake */
        $fake = $this->fakeUser;
        $fake->given([new PasswordResetTokenCreated($this->user->uuid, $this->user->token)]);
        $fake->when(function (UserAggregate $userAggregate) use ($newPassword) {
            return $userAggregate->resetPassword($newPassword, $this->user->token);
        });
        $is = false;
        $fake->assertRecorded(function ($event) use ($newPassword, $fake, &$is) {
            expect(Hash::check($newPassword, $fake->aggregateRoot()->password_hash))->toBeTrue();

            if ($event instanceof UserPasswordResetted) {
                $is = true;
            }
        });
        expect($is)->toBeTrue();
    });


    test('меняет никнейм', function () {
        $newUsername = $this->user->username . 'New';
        /** @var FakeAggregateRoot $fake */
        $fake = $this->fakeUser;
        $fake->when(function (UserAggregate $userAggregate) use ($newUsername) {
            $repository = UserRepositoryAggregate::fake();
            $repository->given(
                [
                    new UserRegistered(
                        $userAggregate->user_uuid,
                        $userAggregate->username,
                        $userAggregate->name,
                        $userAggregate->email,
                        $userAggregate->password_hash,
                        $userAggregate->remember_token,
                    ),
                ],
            );
            $repository->when(function (UserRepositoryAggregate $userRepositoryAggregate) use ($userAggregate, $newUsername) {
                return $userRepositoryAggregate->changeUsername($userAggregate, $newUsername);
            })->then(function (UserRepositoryAggregate $userRepositoryAggregate) use ($userAggregate, $newUsername) {
                expect($userAggregate->username)->toBe($newUsername);
                $newUser = new stdClass;
                $newUser->uuid = $this->user->uuid;
                $newUser->username = $newUsername;
                $newUser->email = $this->user->email;
                expect($userRepositoryAggregate->users)
                    ->toHaveCount(1)
                    ->toContainEqual($newUser);
            });
        });
    });

    test('не меняет никнейм', function () {
        $newUsername = $this->user->username . 'New';
        /** @var FakeAggregateRoot $fake */
        $fake = $this->fakeUser;
        $fake->when(function (UserAggregate $userAggregate) use ($newUsername) {
            $repository = UserRepositoryAggregate::fake();
            $repository->given(
                [
                    new UserRegistered(
                        Uuid::uuid7(),
                        $newUsername,
                        $userAggregate->name,
                        $userAggregate->email,
                        $userAggregate->password_hash,
                        $userAggregate->remember_token,
                    ),
                    new UserRegistered(
                        $userAggregate->user_uuid,
                        $userAggregate->username,
                        $userAggregate->name,
                        $userAggregate->email,
                        $userAggregate->password_hash,
                        $userAggregate->remember_token,
                    ),
                ],
            );
            $repository->when(function (UserRepositoryAggregate $userRepositoryAggregate) use ($userAggregate, $newUsername) {
                expect(fn() => $userRepositoryAggregate->changeUsername($userAggregate, $newUsername))
                    ->toThrow(DomainException::class);
                return $userRepositoryAggregate;
            })->then(function (UserRepositoryAggregate $userRepositoryAggregate) use ($userAggregate, $newUsername) {
                expect($userAggregate->username)->toBe($this->user->username);
                $newUser = new stdClass;
                $newUser->uuid = $this->user->uuid;
                $newUser->username = $this->user->username;
                $newUser->email = $this->user->email;
                expect($userRepositoryAggregate->users)
                    ->toHaveCount(2)
                    ->toContainEqual($newUser);
            });
            $repository->assertNotApplied(UserUsernameChanged::class);
        });
    });

});

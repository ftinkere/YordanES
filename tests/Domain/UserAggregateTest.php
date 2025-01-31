<?php

declare(strict_types=1);

namespace Illuminate\Tests\Domain;

use App\Aggregates\UserAggregate;
use App\Events\User\PasswordResetTokenCreated;
use App\Events\User\UserInvalidLoginAttempt;
use App\Events\User\UserNotUniqueRegisterAttempted;
use App\Events\User\UserPasswordResetted;
use App\Events\User\UserRegistered;
use App\Events\User\UserVerifiedEmail;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

final class UserAggregateTest extends TestCase
{
    use RefreshDatabase;
    public function __construct(private readonly Hasher $hasher)
    {
    }

    public function test_user_creation(): void
    {
        $mockedUuid = '0193df2c-fbb5-700a-894e-cab034252cf0';
        $mockedToken = 'test_random_token_12345';

        $username = 'test';
        $name = 'Test';
        $email = 'test@yordan.ru';
        $hash = $this->hasher->make('password');

        UserAggregate::fake()
            ->when(function (UserAggregate $userAggregate) use ($mockedToken, $mockedUuid, $name, $username, $email, $hash): void {
                $userAggregate->withGenerators(
                    uuidGenerate: fn(): string => $mockedUuid,
                    tokenGenerate: fn(): string => $mockedToken,
                )
                    ->register($username, $name, $email, $hash)
                ;
            })
            ->assertRecorded([
                new UserRegistered($mockedUuid, $username, $name, $email, $hash, $mockedToken)
            ])
            ->assertNotRecorded(UserVerifiedEmail::class)
        ;

        $date = new Carbon;

        UserAggregate::fake()
            ->when(function (UserAggregate $userAggregate) use ($mockedUuid, $mockedToken, $username, $name, $email, $hash, $date): UserAggregate {
                $userAggregate->withGenerators(
                    uuidGenerate: fn(): string => $mockedUuid,
                    tokenGenerate: fn(): string => $mockedToken,
                )
                    ->register($username, $name, $email, $hash)
                    ->verifyEmail()
                ;

                return $userAggregate;
            })
            ->assertRecorded([
                new UserRegistered($mockedUuid, $username, $name, $email, $hash, $mockedToken),
                new UserVerifiedEmail($mockedUuid)
            ])
            ->then(function ($userAggregate) use ($mockedUuid, $mockedToken, $username, $name, $email, $hash): void {
                /** @var UserAggregate $userAggregate */
                $this->assertEquals($mockedUuid, $userAggregate->user_uuid);
                $this->assertEquals($username, $userAggregate->username);
                $this->assertEquals($name, $userAggregate->name);
                $this->assertEquals($email, $userAggregate->email);
                $this->assertEquals($hash, $userAggregate->password_hash);
                $this->assertEquals($mockedToken, $userAggregate->remember_token);
            })
        ;

        UserAggregate::fake()
            ->when(function (UserAggregate $userAggregate) use ($mockedUuid, $mockedToken, $username, $name, $email): UserAggregate {
                $userAggregate->withGenerators(
                    uuidGenerate: fn(): string => $mockedUuid,
                    tokenGenerate: fn(): string => $mockedToken,
                )
                    ->notUniqueRegisterAttempt($username, $name, $email)
                ;

                return $userAggregate;
            })
            ->assertRecorded([
                new UserNotUniqueRegisterAttempted($username, $name, $email),
            ])
            ->then(function ($userAggregate): void {
                /** @var UserAggregate $userAggregate */
                $this->assertFalse(isset($userAggregate->user_uuid));
                $this->assertFalse(isset($userAggregate->username));
                $this->assertFalse(isset($userAggregate->name));
                $this->assertFalse(isset($userAggregate->email));
                $this->assertFalse(isset($userAggregate->password_hash));
                $this->assertFalse(isset($userAggregate->remember_token));
            })
        ;

        UserAggregate::fake()
            ->when(function (UserAggregate $userAggregate) use ($mockedToken, $mockedUuid, $name, $username, $email, $hash): UserAggregate {
                $userAggregate->withGenerators(
                    uuidGenerate: fn(): string => $mockedUuid,
                    tokenGenerate: fn(): string => $mockedToken,
                )
                    ->register($username, $name, $email, $hash)
                    ->invalidLoginAttempt()
                    ->createPasswordResetToken()
                ;

                return $userAggregate;
            })
            ->assertRecorded([
                new UserRegistered($mockedUuid, $username, $name, $email, $hash, $mockedToken),
                new UserInvalidLoginAttempt($mockedUuid),
                new PasswordResetTokenCreated($mockedUuid, $mockedToken),
            ])
            ->then(function ($userAggregate) use ($mockedToken): void {
                /** @var UserAggregate $userAggregate */
                $this->assertEquals($mockedToken, $userAggregate->reset_password_token);
            })
        ;

        UserAggregate::fake()
            ->when(function (UserAggregate $userAggregate) use ($mockedToken, $mockedUuid, $name, $username, $email, $hash): UserAggregate {
                $userAggregate->withGenerators(
                    uuidGenerate: fn(): string => $mockedUuid,
                    tokenGenerate: fn(): string => $mockedToken,
                )
                    ->register($username, $name, $email, $hash)
                    ->createPasswordResetToken()
                    ->resetPassword('new_password', 'invalid_token')
                ;

                return $userAggregate;
            })
            ->assertRecorded([
                new UserRegistered($mockedUuid, $username, $name, $email, $hash, $mockedToken),
                new PasswordResetTokenCreated($mockedUuid, $mockedToken),
            ])
            ->then(function ($userAggregate) use ($mockedToken): void {
                /** @var UserAggregate $userAggregate */
                $this->assertEquals($mockedToken, $userAggregate->reset_password_token);
            })
            ->assertNotRecorded([
                UserPasswordResetted::class,
            ])
        ;

        UserAggregate::fake()
            ->when(function (UserAggregate $userAggregate) use ($mockedToken, $mockedUuid, $name, $username, $email, $hash): UserAggregate {
                $userAggregate->withGenerators(
                    uuidGenerate: fn(): string => $mockedUuid,
                    tokenGenerate: fn(): string => $mockedToken,
                )
                    ->register($username, $name, $email, $hash)
                    ->verifyEmail('invalid_token')
                ;

                return $userAggregate;
            })
            ->assertRecorded([
                new UserRegistered($mockedUuid, $username, $name, $email, $hash, $mockedToken),
            ])
            ->assertNotRecorded([
                UserVerifiedEmail::class,
            ])
        ;

    }
}

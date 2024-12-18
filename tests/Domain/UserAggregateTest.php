<?php

namespace Illuminate\Tests\Domain;

use App\Aggregates\UserAggregate;
use App\Events\User\PasswordResetTokenCreated;
use App\Events\User\UserRegistered;
use App\Events\User\UserVerifiedEmail;
use App\Events\UserInvalidLoginAttempt;
use App\Events\UserNotUniqueRegisterAttempted;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserAggregateTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_creation()
    {
        $mockedUlid = '01HXYZ123ABCDEF8901234';
        $mockedToken = 'test_random_token_12345';

        $username = 'test';
        $name = 'Test';
        $email = 'test@yordan.ru';
        $hash = Hash::make('password');

        UserAggregate::fake()
            ->when(function (UserAggregate $userAggregate) use ($mockedToken, $mockedUlid, $name, $username, $email, $hash) {
                $userAggregate->withGenerators(
                    ulidGenerate: fn() => $mockedUlid,
                    tokenGenerate: fn() => $mockedToken,
                )
                    ->register($username, $name, $email, $hash)
                ;
            })
            ->assertRecorded([
                new UserRegistered($mockedUlid, $username, $name, $email, $hash, $mockedToken)
            ])
            ->assertNotRecorded(UserVerifiedEmail::class)
        ;

        $date = new Carbon;

        UserAggregate::fake()
            ->when(function (UserAggregate $userAggregate) use ($mockedUlid, $mockedToken, $username, $name, $email, $hash, $date) {
                $userAggregate->withGenerators(
                    ulidGenerate: fn() => $mockedUlid,
                    tokenGenerate: fn() => $mockedToken,
                )
                    ->register($username, $name, $email, $hash)
                    ->verifyEmail($date)
                ;

                return $userAggregate;
            })
            ->assertRecorded([
                new UserRegistered($mockedUlid, $username, $name, $email, $hash, $mockedToken),
                new UserVerifiedEmail($mockedUlid, $date)
            ])
            ->then(function ($userAggregate) use ($mockedUlid, $mockedToken, $username, $name, $email, $hash, $date) {
                /** @var UserAggregate $userAggregate */
                $this->assertEquals($mockedUlid, $userAggregate->ulid);
                $this->assertEquals($username, $userAggregate->username);
                $this->assertEquals($name, $userAggregate->name);
                $this->assertEquals($email, $userAggregate->email);
                $this->assertEquals($hash, $userAggregate->password_hash);
                $this->assertEquals($mockedToken, $userAggregate->remember_token);
            })
        ;

        UserAggregate::fake()
            ->when(function (UserAggregate $userAggregate) use ($mockedUlid, $mockedToken, $username, $name, $email, $date) {
                $userAggregate->withGenerators(
                    ulidGenerate: fn() => $mockedUlid,
                    tokenGenerate: fn() => $mockedToken,
                )
                    ->notUniqueRegisterAttempt($username, $name, $email, $date)
                ;

                return $userAggregate;
            })
            ->assertRecorded([
                new UserNotUniqueRegisterAttempted($username, $name, $email, $date),
            ])
            ->then(function ($userAggregate) {
                /** @var UserAggregate $userAggregate */
                $this->assertFalse(isset($userAggregate->ulid));
                $this->assertFalse(isset($userAggregate->username));
                $this->assertFalse(isset($userAggregate->name));
                $this->assertFalse(isset($userAggregate->email));
                $this->assertFalse(isset($userAggregate->password_hash));
                $this->assertFalse(isset($userAggregate->remember_token));
            })
        ;

        UserAggregate::fake()
            ->when(function (UserAggregate $userAggregate) use ($date, $mockedToken, $mockedUlid, $name, $username, $email, $hash) {
                $userAggregate->withGenerators(
                    ulidGenerate: fn() => $mockedUlid,
                    tokenGenerate: fn() => $mockedToken,
                )
                    ->register($username, $name, $email, $hash)
                    ->invalidLoginAttempt($date)
                    ->createPasswordResetToken($date)
                ;

                return $userAggregate;
            })
            ->assertRecorded([
                new UserRegistered($mockedUlid, $username, $name, $email, $hash, $mockedToken),
                new UserInvalidLoginAttempt($mockedUlid, $date),
                new PasswordResetTokenCreated($mockedUlid, $mockedToken, $date),
            ])
            ->then(function ($userAggregate) use ($mockedToken) {
                /** @var UserAggregate $userAggregate */
                $this->assertEquals($mockedToken, $userAggregate->reset_password_token);
            })
        ;

    }
}

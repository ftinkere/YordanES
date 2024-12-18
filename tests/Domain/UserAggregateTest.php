<?php

namespace Illuminate\Tests\Domain;

use App\Aggregates\UserAggregate;
use App\Events\User\UserRegistered;
use App\Events\User\UserVerifiedEmail;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\RefreshDatabaseState;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserAggregateTest extends TestCase
{
    use RefreshDatabase;

    protected function refreshTestDatabase()
    {
        if (! RefreshDatabaseState::$migrated) {
            $this->artisan('migrate:refresh-both');

            $this->app[Kernel::class]->setArtisan(null);

            RefreshDatabaseState::$migrated = true;
        }

        $this->beginDatabaseTransaction();
    }

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
            })
            ->assertRecorded([
                new UserRegistered($mockedUlid, $username, $name, $email, $hash, $mockedToken),
                new UserVerifiedEmail($mockedUlid, $date)
            ])
        ;

    }
}

<?php

declare(strict_types=1);

namespace Tests\Domain;

use App\Aggregates\UserAggregate;
use App\Aggregates\UserRepositoryAggregate;
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
use App\Services\contracts\RandomInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Mockery\MockInterface;
use Ramsey\Uuid\UuidFactoryInterface;

final class UserAggregateTest extends TestCase
{
    use RefreshDatabase;

    protected UuidFactoryInterface $uuidFactory;
    protected RandomInterface $random;

    public function test_user_creation(): void
    {
        $mockedUuid = '0193df2c-fbb5-700a-894e-cab034252cf0';
        $mockedToken = 'test_random_token_12345';
        $username = 'test';
        $name = 'Test';
        $email = 'test@yordan.ru';
        $password ='password';
        $hash = Hash::make($password);
        $date = new Carbon;

        $this->mock(UuidFactoryInterface::class, function (MockInterface $mock) use ($mockedUuid) {
            $mock->allows('uuid7')->andReturns($mockedUuid);
        });
        $this->mock(RandomInterface::class, function (MockInterface $mock) use ($mockedToken) {
            $mock->allows('randomString')->andReturns($mockedToken);
        });

        // Регистрация
        $repository = UserRepositoryAggregate::fake();
        $repository->when(function (UserRepositoryAggregate $userRepositoryAggregate) use ($hash, $email, $name, $username) {
            return $userRepositoryAggregate->register($username, $name, $email, $hash);
        })->then(function (UserAggregate $userAggregate) use ($password, $name, $username, $email, $mockedUuid) {
            $this->assertInstanceOf(UserAggregate::class, $userAggregate);
            $this->assertEquals($mockedUuid, $userAggregate->user_uuid);
            $this->assertEquals($email, $userAggregate->email);
            $this->assertEquals($username, $userAggregate->username);
            $this->assertEquals($name, $userAggregate->name);
            $this->assertTrue(Hash::check($password, $userAggregate->password_hash));
        });

        return;

        // Пользователь регистрируется
        // Почта верифицируется
        // Данные занесены верно
        UserAggregate::fake()
            ->when(function (UserAggregate $userAggregate) use ($username, $name, $email, $hash): UserAggregate {
                $userAggregate
                    ->register($username, $name, $email, $hash)
                    ->verifyEmail()
                ;

                return $userAggregate;
            })
            ->assertRecorded([
                new UserRegistered($mockedUuid, $username, $name, $email, $hash, $mockedToken),
                new UserVerifiedEmail($mockedUuid)
            ])
            ->then(function ($userAggregate) use ($mockedToken, $mockedUuid, $username, $name, $email, $hash): void {
                /** @var UserAggregate $userAggregate */
                $this->assertEquals($mockedUuid, $userAggregate->user_uuid);
                $this->assertEquals($username, $userAggregate->username);
                $this->assertEquals($name, $userAggregate->name);
                $this->assertEquals($email, $userAggregate->email);
                $this->assertEquals($hash, $userAggregate->password_hash);
                $this->assertEquals($mockedToken, $userAggregate->remember_token);
            })
        ;

        // Попытка зарегаться под уже существующим ником
        UserAggregate::fake()
            ->when(function (UserAggregate $userAggregate) use ($username, $name, $email): UserAggregate {
                $userAggregate
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

        // Регистрация, невалидный вход и создание токена восстановления пароля
        UserAggregate::fake()
            ->when(function (UserAggregate $userAggregate) use ($name, $username, $email, $hash): UserAggregate {
                $userAggregate
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

        // Попытка восстановить пароль с неправильным токеном
        $newPassword = 'new_password';
        UserAggregate::fake()
            ->when(function (UserAggregate $userAggregate) use ($newPassword, $mockedToken, $mockedUuid, $name, $username, $email, $hash): UserAggregate {
                $userAggregate
                    ->register($username, $name, $email, $hash)
                    ->createPasswordResetToken()
                    ->resetPassword($newPassword, 'invalid_token')
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

        // Смена пароля
        $newPassword = 'new_password';
        UserAggregate::fake()
            ->when(function (UserAggregate $userAggregate) use ($newPassword, $mockedToken, $mockedUuid, $name, $username, $email, $hash): UserAggregate {
                $userAggregate
                    ->register($username, $name, $email, $hash)
                    ->createPasswordResetToken()
                    ->resetPassword($newPassword, $mockedToken)
                ;

                return $userAggregate;
            })
            ->assertEventRecorded(new UserRegistered($mockedUuid, $username, $name, $email, $hash, $mockedToken))
            ->assertEventRecorded(new PasswordResetTokenCreated($mockedUuid, $mockedToken))
            ->then(function ($userAggregate) use ($newPassword): void {
                /** @var UserAggregate $userAggregate */
                $this->assertTrue(Hash::check($newPassword, $userAggregate->password_hash));
            })
        ;

        // Попытка подтвердить почту с неправильным токеном
        UserAggregate::fake()
            ->when(function (UserAggregate $userAggregate) use ($mockedToken, $mockedUuid, $name, $username, $email, $hash): UserAggregate {
                $userAggregate
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

        // Смена никнейма
        $newUsername = 'new_username';
        UserAggregate::fake()
            ->when(function (UserAggregate $userAggregate) use ($newUsername, $mockedToken, $mockedUuid, $name, $username, $email, $hash): UserAggregate {
                $userAggregate
                    ->register($username, $name, $email, $hash)
                    ->changeUsername($newUsername)
                ;

                return $userAggregate;
            })
            ->assertRecorded([
                new UserRegistered($mockedUuid, $username, $name, $email, $hash, $mockedToken),
                new UserUsernameChanged($mockedUuid, $username, $newUsername),
            ])
            ->then(function ($userAggregate) use ($newUsername): void {
                /** @var UserAggregate $userAggregate */
                $this->assertEquals($newUsername, $userAggregate->username);
            })
        ;

        // Чек события входа
        UserAggregate::fake()
            ->when(function (UserAggregate $userAggregate) use ($password, $newUsername, $mockedToken, $mockedUuid, $name, $username, $email, $hash): UserAggregate {
                $userAggregate
                    ->register($username, $name, $email, $hash)
                    ->login($password)
                ;

                return $userAggregate;
            })
            ->assertRecorded([
                new UserRegistered($mockedUuid, $username, $name, $email, $hash, $mockedToken),
                new UserLoggedIn($mockedUuid),
            ])
        ;

        // Чек события входа с неправильным паролем
        UserAggregate::fake()
            ->when(function (UserAggregate $userAggregate) use ($password, $newUsername, $mockedToken, $mockedUuid, $name, $username, $email, $hash): UserAggregate {
                $userAggregate
                    ->register($username, $name, $email, $hash)
                    ->login($password . '_')
                ;

                return $userAggregate;
            })
            ->assertRecorded([
                new UserRegistered($mockedUuid, $username, $name, $email, $hash, $mockedToken),
                new UserInvalidLoginAttempt($mockedUuid),
            ])
            ->assertNotRecorded([
                UserLoggedIn::class,
            ])
        ;

        // Чек события выхода
        UserAggregate::fake()
            ->when(function (UserAggregate $userAggregate) use ($password, $newUsername, $mockedToken, $mockedUuid, $name, $username, $email, $hash): UserAggregate {
                $userAggregate
                    ->register($username, $name, $email, $hash)
                    ->login($password)
                    ->logout()
                ;

                return $userAggregate;
            })
            ->assertRecorded([
                new UserRegistered($mockedUuid, $username, $name, $email, $hash, $mockedToken),
                new UserLoggedIn($mockedUuid),
                new UserLoggedOut($mockedUuid),
                new UserNewRememberToken($mockedUuid, $mockedToken),
            ])
        ;

        // Чек смены имени
        $newName = 'newName';
        UserAggregate::fake()
            ->when(function (UserAggregate $userAggregate) use ($newName, $password, $newUsername, $mockedToken, $mockedUuid, $name, $username, $email, $hash): UserAggregate {
                $userAggregate
                    ->register($username, $name, $email, $hash)
                    ->login($password)
                    ->changeName($newName)
                ;

                return $userAggregate;
            })
            ->assertRecorded([
                new UserRegistered($mockedUuid, $username, $name, $email, $hash, $mockedToken),
                new UserLoggedIn($mockedUuid),
                new UserNameChanged($mockedUuid, $name, $newName),
            ])
            ->then(function ($userAggregate) use ($newName): void {
                /** @var UserAggregate $userAggregate */
                $this->assertEquals($newName, $userAggregate->name);
            })
        ;

        // Чек смены почты
        $newEmail = 'newemail@yordan.ru';
        UserAggregate::fake()
            ->when(function (UserAggregate $userAggregate) use ($newEmail, $password, $newUsername, $mockedToken, $mockedUuid, $name, $username, $email, $hash): UserAggregate {
                $userAggregate
                    ->register($username, $name, $email, $hash)
                    ->login($password)
                    ->changeEmail($newEmail)
                ;

                return $userAggregate;
            })
            ->assertRecorded([
                new UserRegistered($mockedUuid, $username, $name, $email, $hash, $mockedToken),
                new UserLoggedIn($mockedUuid),
                new UserEmailChanged($mockedUuid, $email, $newEmail),
            ])
            ->then(function ($userAggregate) use ($newEmail, $newName): void {
                /** @var UserAggregate $userAggregate */
                $this->assertEquals($newEmail, $userAggregate->email);
            })
        ;

        // Проставление пути до аватара
        $avatarPath = '/new/path';
        UserAggregate::fake()
            ->when(function (UserAggregate $userAggregate) use ($avatarPath, $password, $newUsername, $mockedToken, $mockedUuid, $name, $username, $email, $hash): UserAggregate {
                $userAggregate
                    ->register($username, $name, $email, $hash)
                    ->login($password)
                    ->setAvatar($avatarPath)
                ;

                return $userAggregate;
            })
            ->assertRecorded([
                new UserRegistered($mockedUuid, $username, $name, $email, $hash, $mockedToken),
                new UserLoggedIn($mockedUuid),
                new UserSettedAvatar($mockedUuid, $avatarPath),
            ])
            ->then(function ($userAggregate) use ($avatarPath, $newEmail, $newName): void {
                /** @var UserAggregate $userAggregate */
                $this->assertEquals($avatarPath, $userAggregate->avatar);
            })
        ;
    }
}

<?php

declare(strict_types=1);

namespace App\Services;

use App\Aggregates\UserAggregate;
use App\Events\User\UserPasswordResetted;
use App\Events\User\UserVerifiedEmail;
use App\Jobs\SendMail;
use App\Mail\EmailConfirmationMail;
use App\Models\User;
use Illuminate\Auth\AuthManager;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Validation\Factory;
use Illuminate\Validation\ValidationException;
use SensitiveParameter;

readonly class UserService
{
    public function __construct(private Factory $validationFactory, private Hasher $hasher, private AuthManager $authManager)
    {
    }
    public function register(
        string $username,
        string $visible_name,
        string $email,
        #[SensitiveParameter] string $password,
    ): bool
    {
        $validator = $this->validationFactory->make(['username' => $username, 'visible_name' => $visible_name, 'email' => $email, 'password' => $password], [
            'username' => 'required|min:3',
            'visible_name' => 'required|filled',
            'email' => 'required|email',
        ]);
        if ($validator->fails()) {
            throw new ValidationException($validator, message: 'Невалидная регистрация');
        }

        $userAggregate = new UserAggregate;
        if (User::checkUnique($username, $email)) {
            $userAggregate
                ->register($username, $visible_name, $email, $this->hasher->make($password))
                ->persist();
            return true;
        }

        $userAggregate
            ->notUniqueRegisterAttempt($username, $visible_name, $email)
            ->persist();
        return false;
    }

    public function login(string $username, #[SensitiveParameter] string $password): bool
    {
        $user = User::where('username', $username)->first();
        if (! $user) {
            return false;
        }

        $userAggregate = UserAggregate::retrieve($user->uuid);

        if (!$user->checkPassword($password)) {
            $userAggregate
                ->invalidLoginAttempt()
                ->persist();
            return false;
        }

        $this->authManager->login($user, true);

        $userAggregate
            ->login($password)
            ->persist();

        return true;
    }

    public function logout(): bool
    {
        /** @var User $user */
        $user = $this->authManager->user();
        if (! $user) {
            return false;
        }

        $userAggregate = UserAggregate::retrieve($user->uuid);

        $user->remember_token = null;
        $this->authManager->logout();

        $userAggregate
            ->logout()
            ->persist();
        return true;
    }

    public function resetPassword(User $user, #[SensitiveParameter] string $password, ?string $token = null): bool
    {
        $userAggregate = UserAggregate::retrieve($user->uuid);
        $events = $userAggregate
            ->resetPassword($password, $token)
            ->persist()
            ->getAppliedEvents();

        return array_any($events, fn($event): bool => $event instanceof UserPasswordResetted);
    }

    public function sendConfirmationEmail(User $user): bool
    {
        // TODO: пока очень не очень, но сойдёт
        $link = '/confirm-email/' . $user->uuid;

        SendMail::dispatch($user->email, new EmailConfirmationMail(
            $user->name,
            $link,
        ));
        return true;
    }

    public function confirmEmail(User $user, ?string $token = null): bool
    {
        $userAggregate = UserAggregate::retrieve($user->uuid);
        $events = $userAggregate
            ->verifyEmail($token)
            ->persist()
            ->getAppliedEvents();
        return array_any($events, fn($event): bool => $event instanceof UserVerifiedEmail);
    }
}

<?php

namespace App\Services;

use App\Aggregates\UserAggregate;
use App\Events\User\UserPasswordResetted;
use App\Events\User\UserVerifiedEmail;
use App\Jobs\SendMail;
use App\Mail\EmailConfirmationMail;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use SensitiveParameter;

class UserService
{
    public function register(
        string $username,
        string $visible_name,
        string $email,
        #[SensitiveParameter] string $password,
    ): bool
    {
        $validator = Validator::make(compact('username', 'visible_name', 'email', 'password'), [
            'username' => 'required|min:3',
            'visible_name' => 'required|filled',
            'email' => 'required|email',
        ]);
        if ($validator->fails()) {
            throw new ValidationException($validator, message: 'Невалидная регистрация');
        }

        $user = new UserAggregate;
        if (User::checkUnique($username, $email)) {
            $user
                ->register($username, $visible_name, $email, Hash::make($password))
                ->persist();
            return true;
        }
        $user
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

        Auth::login($user, true);

        $userAggregate
            ->login($password)
            ->persist();

        return true;
    }

    public function logout(): bool
    {
        $user = Auth::user();
        if (! $user) {
            return false;
        }
        $userAggregate = UserAggregate::retrieve($user->uuid);

        $user->remember_token = null;
        Auth::logout();

        $userAggregate
            ->logout()
            ->persist();
        return true;
    }

    public function resetPassword(User $user, #[\SensitiveParameter] string $password, ?string $token = null): bool
    {
        $userAggregate = UserAggregate::retrieve($user->uuid);
        $events = $userAggregate
            ->resetPassword($password, $token)
            ->persist()
            ->getAppliedEvents();

        return array_any($events, fn($event) => $event instanceof UserPasswordResetted);
    }

    public function sendConfirmationEmail(User $user): bool
    {
        // TODO: пока очень не очень, но сойдёт
        $link = "/confirm-email/{$user->uuid}";

        SendMail::dispatch($user->email, new EmailConfirmationMail(
            $user->visible_name,
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
        return array_any($events, fn($event) => $event instanceof UserVerifiedEmail);
    }
}

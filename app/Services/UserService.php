<?php

namespace App\Services;

use App\Aggregates\UserAggregate;
use App\Events\User\UserLoggedOut;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use SensitiveParameter;

class UserService
{
    public function register(string $username, string $visible_name, string $email, #[SensitiveParameter] string $password): bool
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
        $userAggregate = UserAggregate::retrieve($user->uuid);

        if (! $user->checkPassword($password)) {
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
        Auth::logout();

        event(new UserLoggedOut($user->uuid));
        return true;
    }
}

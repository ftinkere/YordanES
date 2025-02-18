<?php

declare(strict_types=1);

namespace App\Services;

use App\Aggregates\UserAggregate;
use App\Aggregates\UserRepositoryAggregate;
use App\Jobs\SendMail;
use App\Mail\EmailConfirmationMail;
use App\Models\User;
use Exception;
use Illuminate\Auth\AuthManager;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Validation\Factory;
use Illuminate\Validation\ValidationException;
use InvalidArgumentException;
use SensitiveParameter;

readonly class UserService
{
    public function __construct(
        private Factory $validationFactory,
        private Hasher $hasher,
        private AuthManager $authManager,
        private UserRepositoryAggregate $userRepository,
    )
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
            throw new ValidationException($validator);
        }

        if (User::where(['username' => $username])->exists()) {
            $validator->errors()->add('username', 'Такой никнейм уже существует');
            throw new ValidationException($validator);
        }

        try {
            $this->userRepository
                ->register($username, $visible_name, $email, $this->hasher->make($password))
                ->persist();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function login(string $username, #[SensitiveParameter] string $password): bool
    {
        $user = User::where('username', $username)->first();
        if (! $user) {
            return false;
        }

        $userAggregate = $this->userRepository->user($user->uuid);

        try {
            $userAggregate
                ->login($password)
                ->persist();

            $this->authManager->login($userAggregate->model(), true);

            return true;
        } catch (InvalidArgumentException $e) {
            return false;
        }
    }

    public function logout(): bool
    {
        /** @var User $user */
        $user = $this->authManager->user();
        if (! $user) {
            return false;
        }

        $userAggregate = $this->userRepository->user($user->uuid);

        $userAggregate
            ->logout()
            ->persist();
        $user->remember_token = null;
        $this->authManager->logout();

        return true;
    }

    public function resetPassword(User $user, #[SensitiveParameter] string $password, ?string $token = null): bool
    {
        try {
            $this->userRepository
                ->user($user->uuid)
                ->resetPassword($password, $token)
                ->persist();

            return true;
        } catch (InvalidArgumentException $e) {
            return false;
        }
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
        try {
            UserAggregate::retrieve($user->uuid)
                ->verifyEmail($token)
                ->persist();
            return true;
        } catch (InvalidArgumentException $e) {
            return false;
        }
    }
}

<?php

declare(strict_types=1);

namespace App\Livewire\Auth;

use App\Models\PasswordResetToken;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Session\SessionManager;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Title('Восстановление пароля')]
class ResetPasswordPage extends Component
{
    #[Locked]
    public string $uuid;
    public function __construct(private readonly SessionManager $sessionManager, private readonly Factory $viewFactory)
    {
    }

    #[Locked]
    public string $token;

    #[Validate('required|min:8')]
    public string $password;

    #[Validate('required|min:8|same:password')]
    public string $password_repeat;

    public function resetPassword(UserService $userService): void
    {
        $this->validate();

        $user = User::getByUuid($this->uuid);
        if (! $user instanceof User || ! $userService->resetPassword($user, $this->password, $this->token)) {
            $this->sessionManager->flash('message', 'Ошибка восстановления пароля');
        }

        $this->redirect('/login');
    }

    public function mount(string $uuid, Request $request): void
    {
        $this->uuid = $uuid;
        $this->token = $request->query('token');

        $user = User::getByUuid($this->uuid);
        $token = PasswordResetToken::where('reset_token', $this->token)->first();
        if (! $user instanceof User || ! $token || $token->user_uuid !== $user->uuid) {
            $this->sessionManager->flash('message', 'Ошибка восстановления пароля');
            $this->redirect('/');
        }
    }

    public function render(): Factory|Application|View|\Illuminate\View\View
    {
        return $this->viewFactory->make('livewire.auth.reset-password-page');
    }
}

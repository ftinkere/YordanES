<?php

declare(strict_types=1);

namespace App\Livewire\Auth;

use App\Services\UserService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Session\SessionManager;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Title('Вход')]
class LoginPage extends Component
{
    #[Validate('required|filled')]
    public string $username;

    #[Validate('required')]
    public string $password;
    public function __construct(private readonly SessionManager $sessionManager, private readonly Factory $viewFactory)
    {
    }

    public function login(UserService $userService): void
    {
        $this->validate();

        if ($userService->login($this->username, $this->password)) {
            $this->sessionManager->regenerate();
            $this->redirect('/');
        } else {
            $this->addError('password', 'Неправильный логин или пароль');
        }
    }

    public function render(): Factory|Application|View|\Illuminate\View\View
    {
        return $this->viewFactory->make('livewire.auth.login-page');
    }
}

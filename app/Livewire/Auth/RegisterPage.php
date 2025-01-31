<?php

declare(strict_types=1);

namespace App\Livewire\Auth;

use App\Services\UserService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Title('Регистрация')]
class RegisterPage extends Component
{
    #[Validate('required|min:3')]
    public string $username;

    #[Validate('required')]
    public string $visible_name;

    #[Validate('required|email')]
    public string $email;

    #[Validate('required|min:8')]
    public string $password;

    #[Validate('required|same:password')]
    public string $password_repeat;
    public function __construct(private readonly Factory $viewFactory)
    {
    }

    public function register(UserService $userService): void
    {
        $this->validate();

        if ($userService->register($this->username, $this->visible_name, $this->email, $this->password)) {
            $this->redirect('/');
        } else {
            $this->redirect('/message?text=Nope');
        }
    }

    public function render(): Factory|Application|View|\Illuminate\View\View
    {
        return $this->viewFactory->make('livewire.auth.register-page');
    }
}

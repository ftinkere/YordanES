<?php

namespace App\Livewire\Auth;

use App\Services\UserService;
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

    public function login(UserService $service)
    {
        $this->validate();

        if ($service->login($this->username, $this->password)) {
            $this->redirect('/');
        } else {
            $this->addError('password', 'Неправильынй логин или пароль');
        }
    }

    public function render()
    {
        return view('livewire.auth.login-page');
    }
}

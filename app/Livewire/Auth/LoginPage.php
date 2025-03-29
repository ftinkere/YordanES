<?php

namespace App\Livewire\Auth;

use App\Models\User;
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

    public function login(): void
    {
        $this->validate();

        $user = User::where(['username' => $this->username])->first();

        if ($user?->login($this->password)) {
            session()->regenerate();
            $this->redirect('/');
        } else {
            $this->addError('password', 'Неправильный логин или пароль');
        }
    }

    public function render()
    {
        return view('livewire.auth.login-page');
    }
}

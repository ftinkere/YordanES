<?php

namespace App\Livewire\Auth;

use App\Services\UserService;
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

    public function register(UserService $service)
    {
        $this->validate();

        if ($service->register($this->username, $this->visible_name, $this->email, $this->password)) {
            $this->redirect('/');
        } else {
            $this->redirect('/message?text=Nope');
        }
    }

    public function render()
    {
        return view('livewire.auth.register-page');
    }
}

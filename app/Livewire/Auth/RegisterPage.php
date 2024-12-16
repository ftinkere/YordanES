<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Title('Регистрация')]
class RegisterPage extends Component
{
    #[Validate('required')]
    public string $username;

    #[Validate('required')]
    public string $visible_name;

    #[Validate('required')]
    public string $email;

    #[Validate('required')]
    public string $password;

    #[Validate('required|same:password')]
    public string $password_repeat;

    public function register()
    {
        $this->validate(messages: [
            'required' => 'Поле обязательно',
            'same' => 'Пароли не совпадают',
        ]);

        $user = User::register($this->username, $this->visible_name, $this->email, Hash::make($this->password));

        $user
            ? $this->redirect('/')
            : $this->redirect('/message?text=Nope');
    }

    public function render()
    {
        return view('livewire.auth.register-page');
    }
}

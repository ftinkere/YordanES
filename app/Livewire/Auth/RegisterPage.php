<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Validate;
use Livewire\Component;

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
        $this->validate();

        $user = User::register($this->username, $this->visible_name, $this->email, Hash::make($this->password));

        $user
            ? $this->redirect("/message?text={$user->ulid}_{$user->username}")
            : $this->redirect('/message?text=Nope');
    }

    public function render()
    {
        return view('livewire.auth.register-page');
    }
}

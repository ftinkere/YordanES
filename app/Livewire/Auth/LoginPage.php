<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Livewire\Attributes\Validate;
use Livewire\Component;

class LoginPage extends Component
{
    #[Validate('required')]
    public string $username;

    #[Validate('required')]
    public string $password;

    public function login()
    {
        $this->validate();

        $user = User::login($this->username, $this->password);

        $user
            ? $this->redirect("/message?text={$user->ulid}_{$user->username}")
            : $this->redirect('/message?text=Nope');
    }

    public function render()
    {
        return view('livewire.auth.login-page');
    }
}
